<?php
$this->pageTitle=Yii::app()->name.' - ' . $title;
$this->breadcrumbs=array($title);

if(isset($model)) 
{
	$errorSummary = (isset($model->errors) && count($model->errors))? 
		$m->render('documents/errorSummary',array(
				'errors' => utils::toAttributeArray($model->errors)
			)):false;
	///prepend $items for mustache
	$_items = array(/*'cid'=>$cid,*/ 'model'=>$model, 'labels'=>$model->attributeLabels());

	$options = array(
		//'model' => $category, 
		'errorSummary' =>$errorSummary,
		'labels' => /*$category*/Category::model()->attributeLabels(),
		'productDetails' => $_items,
		'title' => Yii::t('app','Product'),
		/*
		'localBreadcrumbs' => $this->widget('CategoryBreadcrumbs', array(
			'links'=>Category::get_category_path($cid),
			'disableLast' => false,
			), true) 
		*/	
	);

	$this->mustacheRender('index', 'catalog', $options);
}else
{
	$options = array(
		'title' => Yii::t('app','Product'),
		
		'breadcrumbs' => $this->widget('CategoryBreadcrumbs', array(
			'links'=>Category::get_category_path($id),
			'disableLast' => false,
			), true) 
			
	);
	$this->mustacheRender(/*$template*/'product/notFound', 'catalog', $options);
}	
?>
