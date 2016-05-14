<?php

class AccountController extends Controller
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
		);
	}
	
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array( 
					'login', 'logout', 'signup', 'forgotPassword','settings','password', 'activate'),
				'users'=>array('*'),
			),
			/*
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete',),
				'users'=>array('admin'),
			),
			*/
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	
	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST) && count($_POST))
		{
			$_POST['rememberMe'] = isset($_POST['rememberMe']) && ($_POST['rememberMe'] == 'on') ?1:0;

			$model->attributes=$_POST;
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('form',array('model'=>$model, 
			'title'=>Yii::t('app','Login'),
			'template'=>'login',
		));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		User::require_login();
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
	
/*
 * 
 * name: genActivateLink
 * @param string $user
 * @return string
 * 
 */	
	public function genActivateLink($user)
	{
		$link = base64_encode($user->newpassword);
		return Yii::app()->getBaseUrl(true).'/account/activate/id/'.$user->id.'/password/'.$link;
	}
	
	/*
	public function actionMailTest()
	{
		$user = User::getByEmailOrUsername('customer');
		if(null === $user)
			throw new CHttpException(500,  Yii::t('app','Invalid test: unable find user'));
		$datamail['to']=$user->email;  //$user->fullname()." <$user->email>";
		$datamail['subject']='mail test';
		
		$data = array('id'=>$user->id, 
						'fullname'=>$user->fullname(),
						'username'=>$user->username, 
						'password' => $user->password,
						'baseUrl' => Yii::app()->getBaseUrl(true),
						'appName' => Yii::app()->name,
						'activateLink'=> $this->activateLink($user),
					);
		
		$datamail['emailbody'] = 'Привет ! ';//utils::mustacheRender('signup','account/mail', $data);
		$datamail['emailbody'] .= print_r($data,true);
		$datamail['headers']  = 'MIME-Version: 1.0' . "\r\n";
		$datamail['headers'] .= 'Content-type: text/html; charset=utf8' . "\r\n";
		$datamail['headers'] .= 'From: '.(isset(Yii::app()->params['support'])?Yii::app()->params['support']:'postmaster@localhost'). "\r\n";
		//utils::debug_array($datamail);
		
		if(mail(
				$datamail['to'],
				$datamail['subject'],
				$datamail['emailbody'],
				$datamail['headers']
		))
		{
				throw new CHttpException(500,  Yii::t('app','OK test: mail was send successfully'));
		}else
				throw new CHttpException(500,  Yii::t('app','Internal error: unable send email'));
	}
	*/

	public function actionSignup()
	{
		$model=new SignupForm('signup');
		// uncomment the following code to enable ajax-based validation
		if(isset($_POST['ajax']) && $_POST['ajax']==='signup-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		$request = Yii::app()->request;
		if($request->isPostRequest) 
		{
			$model->attributes=$_POST;
			if($model->validate())
			{
				// form inputs are valid, do something here
				$user = new User;
				$user->attributes = $model->attributes;
				$transaction=$user->dbConnection->beginTransaction();
				try
				{
					$user->salt = utils::generate_key(10);
					//convert password to hash
					$user->newpassword = crypt($model->password, $user->salt);
					$user->password = $user->newpassword; 
					//replace first char of hash by sign '*' as a marker about  that user is not in activated state
					$user->save();
					//pack user id and encrypted password to confirm link
					//render message by template and send  
					
					$data = array('id'=>$user->id, 
						'fullname'=>$user->fullname(),
						'username'=>$user->username, 
						'password' => $model->password,
						'baseUrl' => Yii::app()->getBaseUrl(true),
						'appName' => Yii::app()->name,
						'activateLink'=> $this->genActivateLink($user),
					);
					$datamail['to']=$user->email;  //$user->fullname()." <$user->email>";
					$datamail['subject']='Signup Confirmation';
					$datamail['emailbody'] = utils::mustacheRender('signup','account/mail', $data);
					$datamail['headers']  = 'MIME-Version: 1.0' . "\r\n";
					$datamail['headers'] .= 'Content-type: text/plain; charset=utf8' . "\r\n";
					$datamail['headers'] .= 'From: '.(isset(Yii::app()->params['support'])?Yii::app()->params['support']:'postmaster@localhost');
					if(imap_mail(
						$datamail['to'],
						$datamail['subject'],
						$datamail['emailbody'],
						$datamail['headers']
					))
					{
						$transaction->commit();
						$this->render('view',array(
							'title'=>Yii::t('app','Signup Success'),
							'template'=>'signupSuccess',
							'options'=>$data,
						));
						Yii::app()->end();
					}else
						throw new CHttpException(500,  Yii::t('app','Internal error: unable send email'));
				}
				catch(Exception $e)
				{
					$transaction->rollBack();
					throw new CHttpException($e->getCode(),  Yii::t('app','Internal error:'.$e->getMessage()));
				}
				$this->redirect(Yii::app()->user->returnUrl);
			}
		}
		$this->render('form',array('model'=>$model, 
			'title'=>Yii::t('app','Signup'),
			'template'=>'signup',
			'withCaptcha' => true,
		));
	}
	

/*
 * 
 * name: actionForgotPassword
 * @param
 * @return
 * first phase : check model, if ok then generate & send email
 * else just render form again.
 */
	public function actionForgotPassword() 
	{
		$model=new ForgotPasswordForm();		
		$request = Yii::app()->request;
		if($request->isPostRequest) 
		{
			$model->attributes=$_POST;
			if($model->validate())
			{
				// form inputs are valid, do something here
				if($user = User::getByEmailOrUsername($model->emailOrUsername))
				{
					//gen new password
					//pack user id and encrypted new password to confirm link
					//render message by template and send  
					$transaction=$user->dbConnection->beginTransaction();
					try
					{
						$password = utils::generate_key(10);
						//convert password to hash
						$user->newpassword = crypt($password, $user->salt);
						$user->save();
						//pack user id and encrypted password to confirm link
						//render message by template and send  
				
						$data = array('id'=>$user->id, 
							'fullname'=>$user->fullname(),
							'username'=>$user->username, 
							'password' => $password,
							'baseUrl' => Yii::app()->getBaseUrl(true),
							'appName' => Yii::app()->name,
							'activateLink'=> $this->activateLink($user),
						);
						$datamail['to']=$user->email;  //$user->fullname()." <$user->email>";
						$datamail['subject']='Password Recovery';
						$datamail['emailbody'] = utils::mustacheRender('forgotPassword','account/mail', $data);
						$datamail['headers']  = 'MIME-Version: 1.0' . "\r\n";
						$datamail['headers'] .= 'Content-type: text/plain; charset=utf8' . "\r\n";
						$datamail['headers'] .= 'From: '.(isset(Yii::app()->params['support'])?Yii::app()->params['support']:'postmaster@localhost');
						if(imap_mail(
							$datamail['to'],
							$datamail['subject'],
							$datamail['emailbody'],
							$datamail['headers']
						))
						{
							$transaction->commit();
							$this->render('view',array(
								'title'=>Yii::t('app','Password Recovery Success'),
								'template'=>'forgotPasswordSuccess',
								'options'=>$data,
							));
							//Yii::app()->end();
						}else
							throw new CHttpException(500,  Yii::t('app','Internal error: unable send email'));
					}
					catch(Exception $e)
					{
						$transaction->rollBack();
						throw new CHttpException($e->getCode(),  Yii::t('app','Internal error:'.$e->getMessage()));
					}
					$this->redirect(Yii::app()->user->returnUrl); 
				}else
				{
					//throw new CHttpException(500,  Yii::t('app','User or email not found'));
					$model->addError('emailOrUsername', 'User or email not found');
				}
				
			}//else just render again
		}
		$this->render('form',array('model'=>$model, 
			'title'=>Yii::t('app','Password Recovery'),
			'template'=>'forgotPassword',
			'withCaptcha' => true,
		));
	}
	
	public function actionActivate($id, $password)
	{
		
		//second phase - user confirm new password
		$user= User::model()->findByPK($id);
		if(null === $user )
			throw new CHttpException(200,  Yii::t('app','User not found'));
		if(Yii::app()->request->requestType === 'GET') 
		{
			if(isset($user->newpassword))
			{
				$password = base64_decode($password);
				//Yii::log('After decode password='.$password.' and user->password='.$user->password,'warning');
				if($user->newpassword === $password)
				{
					$user->password = $user->newpassword;
					$user->newpassword = null;
					$user->save();
				}else
					throw new CHttpException(200,  Yii::t('app','Invalid activation parameters'));
			}
			//just redirect
			$this->redirect(Yii::app()->getBaseUrl(true));		
		}
		throw new CHttpException(400,  Yii::t('app','Page not found'));
	}
	
	public function actionPassword()
	{
		User::require_login();
		$model=new PasswordForm();
		if(Yii::app()->request->isPostRequest) 
		{
			$model->attributes=$_POST;
			$user = User::current();
			$data = array('id'=>$user->id, 
				'fullname'=>$user->fullname(),
				'username'=>$user->username, 
				'baseUrl' => Yii::app()->getBaseUrl(true),
				'appName' => Yii::app()->name,
			);
			if($model->validate())
			{
				// form inputs are valid
				//pack user id and encrypted new password to confirm link
				//render message by template and send  
				$transaction=$user->dbConnection->beginTransaction();
				try
				{
					//convert password to hash and save
					$user->password = crypt($model->newPassword, $user->salt);
					$user->newpassword = null; 
					$user->save();
					$transaction->commit();
 
					//redirect to index page
					$this->redirect(Yii::app()->getBaseUrl(true));
				}
				catch(Exception $e)
				{
					$transaction->rollBack();
					throw new CHttpException($e->getCode(),  Yii::t('app','Internal error:'.$e->getMessage()));
				}
			}
		}
		$this->render('form',array('model'=>$model, 
			'title'=>Yii::t('app','Password Change'),
			'template'=>'password',
			'withCaptcha' => true,
		));
	}
	
	
	
	public function actionSettings()
	{
		User::require_login();
		$model=new SettingsForm();
		$user = User::current();
		$model->attributes = $user->attributes;
	
		if(Yii::app()->request->isPostRequest) 
		{
			$model->attributes=$_POST;
			if($model->validate())
			{
				// form inputs are valid
				//pack user id and encrypted new password to confirm link
				//render message by template and send  
				$transaction=$user->dbConnection->beginTransaction();
				try
				{
					//convert password to hash and save
					$user->attributes = $model->attributes;
					$user->save();
					$transaction->commit();
					//redirect to index page
					$this->redirect(Yii::app()->getBaseUrl(true));
				}
				catch(Exception $e)
				{
					$transaction->rollBack();
					throw new CHttpException($e->getCode(),  Yii::t('app','Internal error:'.$e->getMessage()));
				}
			}
		}
		$this->render('form',array('model'=>$model, 
			'title'=>Yii::t('app','Settings'),
			'template'=>'settings',
			'withCaptcha' => true,
		));
	}
}
