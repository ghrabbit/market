<?php
$this->pageTitle=Yii::app()->name.' - ' . $title;
//$this->breadcrumbs=array($title);

if(isset($model)) 
{
	$errorSummary = (isset($model->errors) && count($model->errors))? 
		$m->render('documents/errorSummary',array(
				'errors' => utils::toAttributeArray($model->errors)
			)):false;
	///prepend $items for mustache
	$_items = array();
	/**/
	foreach($items as $one)
	{
		$labels = $one->attributeLabels();
		foreach($items as $item)
			$_items[] = array('cid'=>$model->id, 'model'=>$item, 'labels'=>$labels);
		break;
	}

	$options = array(
		'model' => $model, 
		'errorSummary' =>$errorSummary,
		'labels' => $model->attributeLabels(),
		'products' => $_items,
		'title' => Yii::t('app','Product'),
		
		'localBreadcrumbs' => $this->widget('CategoryBreadcrumbs', array(
			'links'=>Category::get_category_path($id),
			'disableLast' => false,
			), true), 
		
		'pager' => $this->widget('Pager', array('pages' => $pages, 'maxButtonCount'=>5), true),	
		'categoryTop' => array('model'=>$model, 'labels'=>$model->attributeLabels()),	
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
