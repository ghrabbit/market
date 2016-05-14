<?php

/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	
	public $pageTitle;
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	public $topSideMenu;
	
	public $layoutTemplate='main';
	
	public function layoutTemplatePath() { return Yii::app()->basePath.DIRECTORY_SEPARATOR.'templates'.DIRECTORY_SEPARATOR ;}
	public function layoutLangTemplatePath() { return Yii::app()->basePath.DIRECTORY_SEPARATOR.'templates'.
		DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.Yii::app()->language.DIRECTORY_SEPARATOR;}
	
	private function templateLoaders($dir)
	{
	  $app = Yii::app();
	  $loaders = array();
	  $lang_dir = $app->basePath.'/templates/lang/'.Yii::app()->language.DIRECTORY_SEPARATOR.$dir;
	  if (is_dir($lang_dir)) 
		$loaders[] = new Mustache_Loader_FilesystemLoader($lang_dir);
	  $loaders[] = new Mustache_Loader_FilesystemLoader($app->basePath.'/templates/'.$dir); 	
	  return $loaders;
	}
	
	//attentions! do not use partials! use hier accessible templates (thanx sir/mam)
	public function mustacheRender($view, $searchPath, $rgs=null)
	{
		$config = array(
			'cache'=> Yii::app()->basePath.'/runtime/Mustache/cash',

		);
		
		$app = Yii::app();
		$rgs = array_merge($rgs, array( 
			'baseUrl'=> $app->getBaseUrl(true),
			'staticUrl'=> $app->baseUrl,
			'vendorUrl'=> '/assets/bower_components',
			'appName' => $app->name,
			'username'=>$app->user->name, 
			'authenticated'=>User::is_logged_in(),
			'pageTitle'=>$this->pageTitle,
			'rights'=>Yii::t('app','rights',array(':d'=>'2013'/*date('Y')*/)),
		));	
		
		if(isset($this->layoutTemplate))
		{
			$config['loader'] = new Mustache_Loader_CascadingLoader($this->templateLoaders('layouts'));

			$rgs['content']=$this->mustacheRenderPartial($view,$searchPath,$rgs);
			
			$view = $this->layoutTemplate;
			$rgs['topNavbar']=$this->mustacheRenderPartial('topNavbar', 'layouts',
				array_merge($rgs, array(
					'labels'=>(new TopNavbarHelper)->attributeLabels(),
					'langs'=>array(
						'current'=>Yii::t('lang',$app->language),
						//put hier your own langs
						'en_us'=>Yii::t('lang','en_us'),
						'ru_ru'=>Yii::t('lang','ru_ru'),
					)
				)
			));
			$rgs['userMenu']=$this->mustacheRenderPartial('userMenu', 'layouts',
				array_merge($rgs, array('labels'=>(new UserMenuHelper)->attributeLabels())
			));
			$rgs['cartMenu']=$this->mustacheRenderPartial('cartMenu', 'layouts',
				array_merge($rgs, array('model'=>$app->session['cart'],'labels'=>(new CartMenuHelper)->attributeLabels())
			));
			
			if($this->topSideMenu)
				$rgs['topsideMenu']=$this->mustacheRenderPartial('topsideMenu', $this->topSideMenu['templatePath'],
					array_merge($rgs, $this->topSideMenu['args']));
				
		}else{
			$config['loader'] = new Mustache_Loader_FilesystemLoader($this->layoutTemplatePath().$searchPath);	
		}
		$m = new Mustache_Engine($config);
		echo $m->render($view,$rgs);
	}
	
	public function mustacheRenderPartial($view, $searchPath, $rgs=null)
	{
		$app = Yii::app();
		$config = array(
			'cache'=> $app->basePath.'/runtime/Mustache/cash',
			'loader' => new Mustache_Loader_CascadingLoader($this->templateLoaders($searchPath)),
		);	
		
		

		$m = new Mustache_Engine($config);
		return $m->render($view,$rgs);
	}
	
	public function renderText($view, $searchPath, $rgs=null) 
	{
		return $this->mustacheRenderPartial($view, $searchPath, $rgs);
	}
}
