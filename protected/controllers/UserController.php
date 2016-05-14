<?php

require('AdminController.php');

class UserController extends AdminController
{
	
	public function actionIndex($info = null)
	{
		User::require_login();
		$user = User::current();
		$user->require_role("admin");
		
		$this->pageTitle = Yii::t('app','User Management');
		$this->topSideMenu = array(
			'widget'=>'ModelActions' , 
			'templatePath'=>$this->getId(),
			'args' => array(
				'actions'=>array(),
				'view'=>'actions/user',
			)
		);

		$this->render('index', array('model'=>$user,'items' =>  User::model()->findAll(), 'info' => $info));		
	}
	
	public function actionCreate()
	{
		$this->pageTitle = Yii::t('app','User Management').'/'.Yii::t('app','Create');
		User::require_login();
		$user = User::current();
		$user->require_role("admin");
		
		if(Yii::app()->request->requestType === 'GET') {
			$model = User::model();
		}else
		if(Yii::app()->request->requestType === 'POST')  {
			$model = new User;
			$model->attributes = $_POST;
			
			$model->salt = utils::generate_key(10);
			//convert password to hash
			$model->password = crypt('demo', $model->salt);
			if($model->validate()) 
			{
				$model->save();
				$this->redirect('index');
			}
		}
		$this->render('edit', array('model'=>$model));
	}
	
	public function actionRemove($id)
	{
		User::require_login();
		$user = User::current();
		$user->require_role("admin");
		if($user->id == $id)
			throw new CHttpException(500,  Yii::t('app','Unable remove your own data while login'));
	
		if(Yii::app()->request->requestType === 'GET') {
			$model = User::model()->findByPK($id);

			$content = $this->renderText('deleted','user',array('model'=>$model));
			$model->delete();	
			$this->redirect(array('index', 'info'=>$content)); 
		}
	}
	
	function actionEdit($id) {
		User::require_login();
		$user = User::current();
		$user->require_role("admin");
		
		$this->pageTitle = Yii::t('app','User Management').'/'.Yii::t('app','Edit');
		if(Yii::app()->request->requestType === 'GET') {
			$model = User::model()->findByPk($id);
		}else if(Yii::app()->request->requestType === 'POST') 
		{
			$model = User::model()->findByPk($id);
			$model->attributes = $_POST;
			if($model->validate()) 
			{
				$model->save();
				$this->redirect('index');
			}
		}
		$this->render('edit',array('model'=>$model,));
	} 

}
