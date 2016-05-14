<?php

class SiteController extends Controller
{
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// not using the default layout 'protected/views/layouts/main.php'

		$pageNo = isset($_GET['page'])?$_GET['page']:1;
		$pageSize = isset($_GET['pageSize'])?$_GET['pageSize']:6;
		
		$this->render('index', array(
			'title'=>Yii::t('app','Closeout'),
			'pageNo'=> $pageNo,
			'pageSize' => $pageSize,
		));
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			
			else if ($error['code'] == 401)
				$this->redirect('login');
					
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		//utils::debug_array($_POST);
		$request = Yii::app()->request;
		if($request->isPostRequest) 
		{
			$model->attributes=$_POST;
			if($model->validate())
			{
				//$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				//$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $model->name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				//mail(Yii::app()->params['adminEmail'],$model->subject,$model->body,$headers);
				if(imap_mail(Yii::app()->params['postmaster'],$model->subject,$model->body,$headers))
				{
					//Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
					$this->render('contact',array(
						'model'=>$model,
						'pageTitle'=>'Thank you for contacting us', 
						'postmsg'=>'We will respond to you as soon as possible.',
					));
				}else
					throw new CHttpException(500,  Yii::t('app','Internal error: unable send email'));
			}
		}
		else $this->render('contact',array(
				'model'=>$model, 
				//'errormsg'=>isset($_GET['errormsg'])?$_GET['errormsg']:null,
		));
	}
	
	public function actionLang($id)
	{
		$app = Yii::app();
		$app->setLanguage($id);
		$app->session['ulang']=$id;
		$this->redirect($app->request->urlReferrer);
	}

}
