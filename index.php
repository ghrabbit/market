<?php

// change the following paths if necessary
$yii=dirname(__FILE__).'/protected/vendor/yiisoft/yii/framework/yii.php';
$config=dirname(__FILE__).'/protected/config/main.php';

// remove the following lines when in production mode
defined('YII_DEBUG') or define('YII_DEBUG',true);
// specify how many levels of call stack should be shown in each log message
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

require_once($yii);
$app = Yii::createWebApplication($config);

$vendor_path = dirname(__FILE__).'/protected/vendor/mustache/mustache/src';
$path = $vendor_path.'/Mustache/Autoloader.php';

if (file_exists($path))
{
	
	ini_set('include_path',
		ini_get('include_path').PATH_SEPARATOR.dirname(dirname($path)));

	 // Unregister Yii autoloader
     spl_autoload_unregister(array('YiiBase','autoload'));
 
     // Register Mustache autoloader
     require_once $path;
     Mustache_Autoloader::register($vendor_path);
 
     // Add Yii autoloader again
     spl_autoload_register(array('YiiBase','autoload'));
}

$app->run();
