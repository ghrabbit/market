<?php

class AdminController extends Controller
{
	//public $layout = '/admin/index';
	public $layout = '/layouts/admindex';
	public $user;
	
	public function actionIndex()
	{
		User::require_login();
		$user = User::current();
		utils::debug_array($user->getRoles());
		$user->require_role("admin");
		
		$this->pageTitle = "Sample eshop Administrator";
		$this->render('/admin/_index');
	}

	// -----------------------------------------------------------
	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/
}
