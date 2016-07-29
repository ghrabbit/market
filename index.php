<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/protected/vendor/yiisoft/yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', 3);

require_once($yii);
Yii::setPathOfAlias('vendor',dirname(__FILE__).'/protected/vendor');
Yii::setPathOfAlias('bower',dirname(__FILE__).'/protected/vendor/bower-asset');
$app = Yii::createWebApplication($config);
$bower_url = Yii::app()->baseUrl.'/assets/bower_components';

 $app->clientScript
  //->registerPackage('jquery')
   ->registerScriptFile($bower_url.'/jquery/dist/jquery.min.js')
   ->registerScriptFile($bower_url.'/bootstrap/dist/js/bootstrap.min.js')
   ->registerScriptFile($bower_url.'/selectize/dist/js/standalone/selectize.min.js')  
  ;

//composer autoloading not work!
$mustache_path = dirname(__FILE__).'/protected/vendor/mustache/mustache/src';
$path = $mustache_path.'/Mustache/Autoloader.php';

if (file_exists($path))
{
	
	Yii::setPathOfAlias('mustache', $mustache_path);
	ini_set('include_path',
		ini_get('include_path').PATH_SEPARATOR.dirname(dirname($path)));

	 // Unregister Yii autoloader
     spl_autoload_unregister(array('YiiBase','autoload'));
 
     // Register Mustache autoloader
     require_once $path;
     Mustache_Autoloader::register($mustache_path);
 
     // Add Yii autoloader again
     spl_autoload_register(array('YiiBase','autoload'));
}

if (!isset($app->session['cart'])) {
	$app->session['cart'] = new Cart;
}

if (isset($app->session['ulang'])) {
	$app->language = $app->session['ulang'];
}

//translate all
$app->messages->forceTranslation = true;
$app->user->guestName = isset($app->params['guestName'])?$app->params['guestName']: Yii::t('app','guest');
	
$app->run();
