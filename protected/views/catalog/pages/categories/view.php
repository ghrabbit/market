<?php
//$this->pageTitle=Yii::app()->name.' - ' . $title;
$this->breadcrumbs=array($title);

if(isset($model)) 
{

	$errorSummary = (isset($model->errors) && count($model->errors))? 
		$m->render('documents/errorSummary',array(
				'errors' => utils::toAttributeArray($model->errors)
			)):false;
	///prepend $categories for mustache
	$_categories = array();
	/**/
	$labels = Category::model()->attributeLabels();
	foreach($categories as $cat)
		$_categories[] = array('model'=>$cat, 'labels'=>$labels);

	$this->topSideMenu = array(
		'templatePath'=>'catalog/category',
		'args' => array('model'=>$model, 'items'=>$_categories, 'labels'=>$model->attributeLabels())
	);
	$options = array(
		'model' => $model, 
		'errorSummary' =>$errorSummary,
		'labels' => $model->attributeLabels(),
		'pageTitle'=>$title,
		'title' => Yii::t('app','Category'),
		
		'localBreadcrumbs' => $this->widget('CategoryBreadcrumbs', array(
			'links'=>Category::get_category_path($id),
			'disableLast' => false,
			), true), 
		
		'pager' => $this->widget('Pager', array('pages' => $pages, 'maxButtonCount'=>5), true),	
		'categoryTop' => array('model'=>$model, 'labels'=>$labels),
		'categories' => $_categories,	
	);

	/*
	if(count($_categories))
		$options['categories'] = $_categories;
	else
		$options['categoryDetails'] = array('model'=>$model, 'labels'=>$labels);
	*/	

	$this->mustacheRender('index', 'catalog', $options);
}
?>
