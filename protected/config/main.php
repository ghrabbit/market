<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	//'language' => 'en_us',
	'language' => 'ru_ru',
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Yii eshop sample',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.models.components.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'Enter Your Password Here',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		*/
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'loginUrl'=>array('account/login'),
		),
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'static/<view:\w+>'=>'site/page',
				'contact'=>'site/contact',
				'login'=>'account/login',
				'logout'=>'account/logout',
				'signup'=>'account/signup',
				'password'=>'account/password',
				'settings'=>'account/settings',
				'catalog/category/<id:\d+>/products'=>'catalog/products',
				'catalog/category/<id:\d+>/products/pn<page:\d+>'=>'catalog/products',
				//'catalog/category/<cid:\d+>/productdetails/<id:\d+>'=>'catalog/productdetails',
                //'catalog/productdetails/<id:\d+>'=>'catalog/productdetails',
				'shopping/cart'=>'shopping',
				'site/lang/<id:\w+>'=>'site/lang',
				//
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				//'account/<action:\w+>/<id:\d+>'=>'site/<action>',
				//'account/<action:\w+>'=>'site/<action>',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		*/
		// uncomment the following to use a MySQL database
		/*
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=sample_eshop_dev',
			'emulatePrepare' => true,
			'username' => 'eshop',
			'password' => 'sunsun',
			'charset' => 'utf8',
		),
        */ 
		'db'=>array(
			'connectionString' => 'pgsql:host=localhost;port=5432;dbname=eshop_dev',
			'username' => 'eshop',
			'password' => 'sunsun',
			'charset' => 'utf8',
		),
         
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'webmaster'=>'webmaster@home.local.one',
		'postmaster'=>'postmaster@home.local.one',
		'support'=>'postmaster@home.local.one',
	),
);
