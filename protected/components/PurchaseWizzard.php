<?php

class PurchaseWizzard extends AWizzard {
	
	function __construct()
	{
		$this->_pages = array(
			'customer'=>array('model'=>'CustomerData',
				'template'=>'customer/view',
				'actions'=>array(
					'clear'=>array(),
					'next'=>array('id'=>'paymentSelect','pullRight'=>true)
				),
				'pagename'=>'customer'
			),
			/*	
			'delivery'=>array('model'=>'DeliveryData',
				'actions'=>array('prev'=>array('id'=>'customer'),'next'=>array('id'=>'paymentSelect'),)
			),
			*/
			'paymentSelect'=>array('model'=>'PaymentSelectData',
				'template'=>'paymentSelect',
				'actions'=>array(
					//'next'=>array('id'=>'mailSummary', 'pullRight'=>true),
					'next'=>array('property'=>'paySystem', 'pullRight'=>true),
					/*
					'next'=>array(
						'func'=> function($data)
						{
							//return name of the next page
							
							return $data->paySystem;
						}, 
						'pullRight'=>true),
					*/
					'prev'=>array('id'=>'customer','pullRight'=>true),
				)
			),		
			/*
			'paymentSelect'=>array('model'=>'PaymentSelectData',
				'actions'=>array('prev'=>array('id'=>'delivery'),'next'=>array('property'=>'paySystem'),)
			),	
			'wmm'=>array('model'=>'WmmPaymentData',	
				'actions'=>array('prev'=>array('id'=>'paymentSelect'),'finish'=>array(),)
			),	
			'wmr'=>array('model'=>'WmrPaymentData',
				'actions'=>array('prev'=>array('id'=>'paymentSelect'),'finish'=>array(),)
			),	
			'ym'=>array('model'=>'YmPaymentData', 
				'actions'=>array('prev'=>array('id'=>'paymentSelect'),'finish'=>array(),)
			),
			*/	
			'email'=>array('model'=>'MailSummaryData', 
				'template'=>'mail/summary',
				'actions'=>array(
					'finish'=>array('pullRight'=>true),
					'prev'=>array('id'=>'paymentSelect','pullRight'=>true),
				)
			),
			'ccard'=>array('model'=>'NotImplementedData', 
				'template'=>'notImplemented',
				'actions'=>array(
					//finish is not accessible while template is not implemented
					//'finish'=>array('pullRight'=>true),
					'prev'=>array('id'=>'paymentSelect','pullRight'=>true),
				)
			),
			'wallet'=>array('model'=>'NotImplementedData', 
				'template'=>'notImplemented',
				'actions'=>array(
					//finish is not accessible while template is not implemented
					//'finish'=>array('pullRight'=>true),
					'prev'=>array('id'=>'paymentSelect','pullRight'=>true),
				)
			),
		);
		$this->_pageNames = array_keys($this->_pages);
		$this->data = array();
	}
	
/*
 * 
 * name: getDefaultPage
 * @param
 * @return string
 * 
 */
	public function getDefaultPage() { return $this->_pageNames[0];}
	
/*
 * 
 * name: getPageClass
 * @param $pageId
 * @return string
 * 
 */	
	public function getPageClass($pageId)
	{
		$page = $this->_pages[$pageId];
		$modelClass = $page['model'];
		return $modelClass;
		
	}
	
	public function getActions($pageId)
	{
		$page = $this->_pages[$pageId];
		$actions = $page['actions'];
		return $actions;
	}
	
	public function getData($pageId)
	{
		return isset($this->data[$pageId])?$this->data[$pageId]:$this->factory($pageId);
	}
	
	public function getAllData()
	{
		$ret = array();
		foreach($this->_pageNames as $name)
			$ret[$name] = $this->getData($name);
		return $ret;
	}
	
	public function getTemplate($pageId)
	{
		$page = $this->_pages[$pageId];
		return isset($page['template'])?$page['template']:'notImplemented';
	}
	
	public function prev($pageId)
	{
		$page = $this->_pages[$pageId];
		$model = $page['model'];
		$actions = $page['actions'];
		if($dest = $actions['prev']?$actions['prev']:null)
		{
			foreach($dest as $arg=>$val)
			{
				switch($arg)
				{
						case 'id' : 
							//return data by page id 
							return $val; break;
						case 'property' : 
							//return data by current page property value
							return $page->$val;
							break;
						case 'func' : 
							//return data by call func with arg as current data
							if(isset($val))
								return $val($this->getData($pageId));
							break;	
				}
			}
		}
		return null;
	}
	
	public function next($pageId)
	{
		$page = $this->_pages[$pageId];
		$model = $page['model'];
		$actions = $page['actions'];
		if($dest = $actions['next']?$actions['next']:null)
		{
			foreach($dest as $arg=>$val)
			{
				switch($arg)
				{
						case 'id' : 
							//return data by page id 
							return $val; break;
						case 'property' : 
							//return data by current page property value
							return $this->data[$pageId]->$val;
							break;
				}
			}
		}
		return null;
	}

	/*
	function pageName($page_id)
	{
		if(($page_id <0) || ($page_id >= count($this->_pages)))
			return false;
		return $this->_pageNames[$page_id];
	}
	*/
	
	// The factory method
    public function factory($pageId)
	{
		if(isset($this->_pages[$pageId]))
		{
			$page = $this->_pages[$pageId];  
			if(!is_array($page)) throw new Exception ('Array expected');
			$modelClass = $page['model'];

			//Yii::log("Factory $modelClass was called",'warning');
			if (include_once $modelClass . '.php') {
				return new $modelClass($this);
			} else {
				throw new Exception ("Data class $modelClass  not found");
			}
		}
		throw new Exception ("Page for '$pageId'  not found");
    }

/*
 * 
 * name: save
 * @param $page_id, $data
 * @return
 * this function saves the order information into the session variable
 * $SESSION["orderinfo"].  it is used in the purchase confirmation stage
 */

	function save($page_id, $data) 
	{

		$this->data[$page_id] = $data;
		Yii::app()->session["purchaseWizzard"] = $this;
	}

	static public function load() 
	{

		$info = isset(Yii::app()->session['purchaseWizzard'])?Yii::app()->session['purchaseWizzard']:null;
		if (empty($info)) {
			$className=__CLASS__;
			return new $className; 
		} 
		return $info;
		
	}

/*
 * 
 * name: clear
 * @param
 * @return
 * this function is called to clear the orderinfo session variable, it should
 * be used after an order was successfully completed
 */

	static function clear() {
		Yii::app()->session->remove("purchaseWizzard");
	} 
	
}

?>
