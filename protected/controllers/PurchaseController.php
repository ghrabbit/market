<?php

class PurchaseController extends Controller
{
	
		public function filters()
	{
		return array(
			'accessControl',
		);
	}

/*
 * 
 * name: accessRules
 * @param
 * @return
 * for users
 * – * : any user, including both anonymous and authenticated users.
 * – ? : anonymous users.
 * – @ : authenticated users.
 */

	public function accessRules()
	{
		
		return array(
			//any alow
			array('deny',
				'actions'=>array('complete'),
				'users'=>array('*'),
			),
			//anonymous deny
			array('deny',
				'actions'=>array('index'),
				'users'=>array('?'),
			),
			/*
			array('allow',
				'actions'=>array('delete'),
				'roles'=>array('admin'),
			),
			
			array('deny',
				'actions'=>array('delete'),
				'users'=>array('*'),
			),
			*/
		);
	}	
	
	// -----------------------------------------------------------
	/**
	 *
	 */
	protected function defaultRender()
	{
			$wizzard = PurchaseWizzard::load();
			//default page id
			$page_id=$wizzard->getDefaultPage();
			//render current wizzard page content
			//User::require_login(); 
			$params = array(
				'data' => $wizzard->getData($page_id),
				'cart' => Yii::app()->session['cart'],
				'page_id' => $page_id,
				'actions' => $wizzard->getActions($page_id),
				'template' => $wizzard->getTemplate($page_id),
			);
			$this->render("/purchase/index",$params);
	}
	
	public function actionIndex()
	{
		if(Yii::app()->request->isPostRequest) 
		{
			$func=$_POST['func'];
			switch($func)
			{
				case 'prev': $this->_actionPrev(); break;
				case 'next': $this->_actionNext(); break;
				case 'finish': $this->_actionFinish(); break;	
				default: 
					//clear order
					PurchaseWizzard::clear();
					$this->defaultRender();
				}
		}else $this->defaultRender();

	}
	

	
	public function _actionPrev()
	{

		/* funcs: 1 - prev, 2 - next, 3 -finish
		 */
		$params = array();
		$wizzard = PurchaseWizzard::load();
		
		if(Yii::app()->request->isPostRequest) 
		{
			$page_id=$_POST['page_id']; 
			if($data = $wizzard->factory($page_id))
			{
				$data->attributes = $_POST;
				$wizzard->save($page_id, $data);
				$page_id = $wizzard->prev($page_id);
			}	
		}
		$params['data'] = $wizzard->getData($page_id);
		$params['data']->refresh();
		$params['actions'] = $wizzard->getActions($page_id);
		$params['cart'] = Yii::app()->session['cart'];
		$params['page_id'] = $page_id;
		$params['template'] = $wizzard->getTemplate($page_id);
		$this->render("/purchase/index",$params);
	}
	
	public function _actionNext()
	{

		/* funcs: 1 - prev, 2 - next, 3 -finish
		*/
		//User::require_login(); 
		$params = array();
		$wizzard = PurchaseWizzard::load();
		
		$page_id = $wizzard->getDefaultPage();
		if(Yii::app()->request->isPostRequest) 
		{
			$page_id=$_POST['page_id']; 
			if($data = $wizzard->factory($page_id))
			{
				$data->attributes = $_POST;
				$wizzard->save($page_id, $data);
				if($data->validate())
					$page_id = $wizzard->next($page_id);
				else
					$params['errors'] = $data->errors;
			}				
		}	
		$params['data'] = $wizzard->getData($page_id);
		$params['data']->refresh();
		$params['actions'] = $wizzard->getActions($page_id);
		$params['cart'] = Yii::app()->session['cart'];
		$params['page_id'] = $page_id;
		$params['labels']=$params['data']->attributeLabels();
		$params['template'] = $wizzard->getTemplate($page_id);
		$this->render("/purchase/index",$params);
	}
	
/*
 * 
 * name: actionFinish
 * @param
 * @return
 */
	
	public function _actionFinish()
	{
		//User::require_login();
		$cart = Yii::app()->session['cart'];
		if (!$cart->itemcount()) {
			$this->redirect('shopping/cart');
		}
		
		$wizzard = PurchaseWizzard::load();
		if(Yii::app()->request->isPostRequest) 
		{
		  $page_id=$_POST['page_id']; 
		  if($data = $wizzard->factory($page_id))
		  {
			$data->attributes = $_POST;
			$wizzard->save($page_id, $data);
			if($data->validate())
			{
			  /* we will create the order in our database, then send mail or try to authorize the
			   * payment.  if all was successful, the user's order will have been
			   * completed.
			   */
			  $transaction=Yii::app()->db->beginTransaction();
			  try
			  {
				$order = Order::create($wizzard->getAllData());
				$transaction->commit();
				//clear cart
				$cart->clear();
				//clear wizzard
				PurchaseWizzard::clear();
			  }
		      catch(Exception $e)
		      {
				$transaction->rollBack();
				throw new CHttpException($e->getCode(),  Yii::t('app','Internal error:'.$e->getMessage()));
		      }
			  if($order->payment_system === PaymentSelectData::EMAIL)
			  {	
				if(!$this->sendMail($order))
				  throw new CHttpException(500,  Yii::t('app','Internal error: unable send email:order id #'.$order->id));
				$this->render("/purchase/mail/success",$this->id);  
			  }
			  else
			  {
				$payment_url = Yii::app()->params['payment_url'];
				if(isset($payment_url))
				{
							//start two phase order transaction.
							//first phase: redirect to payment server to authorize the payment
							//second phase: see actionComplete below  
							$this->redirect($payment_url,array(
								'orderid'=>$order->id,
								'amount'=>$order->amount,
							));
				}else
				  throw new CHttpException(404,'The payment server url is undefined.');
			  }
			}
			else //not valid - try again
			{
				//reload page & render errors
				$params['errors'] = $data->errors;
				$params['data'] = $wizzard->getData($page_id);
				$params['data']->refresh();
				$params['actions'] = $wizzard->getActions($page_id);
				$params['cart'] = Yii::app()->session['cart'];
				$params['page_id'] = $page_id;
				$params['labels']=$params['data']->attributeLabels();
				$this->render("/purchase/index",$params);
			}	
		  }				
		}//post request
	}
	
	protected function sendMail($order)
	{
		$link = base64_encode($order->user->password);

		//render message by template and send  
					
		$data = array(
			'order'=>$order,
			'labels'=>array(
				'order'=>$order->attributeLabels(),
				'product'=>Product::model()->attributeLabels()),
				'activateLink' => Yii::app()->getBaseUrl(true).'/purchase/activate/id/'.$order->user->id.'/password/'.$link.'/order_id/'.$order->id,
				'activatePeriod'=>strftime('%H:%M %B %d, %Y %Z',$order->ordered + 12*3600)
		);
		$datamail['to']=$order->email;  //$user->fullname()." <$user->email>";
		$datamail['subject']='Order Confirmation';
		$datamail['emailbody'] = $this->mustacheRenderPartial('order','purchase/mail', $data);
		$datamail['headers']  = 'MIME-Version: 1.0' . "\r\n";
		$datamail['headers'] .= 'Content-type: text/plain; charset=utf8' . "\r\n";
		$datamail['headers'] .= 'From: '.(isset(Yii::app()->params['support'])?Yii::app()->params['support']:'postmaster@localhost');
		return imap_mail(
						$datamail['to'],
						$datamail['subject'],
						$datamail['emailbody'],
						$datamail['headers']
		);
	}
	
	public function actionComplete()
	{
		$orderid = $_POST['order_id'];
        //do something hier
		//change order history
	}

}
