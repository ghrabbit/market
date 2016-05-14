<?php
$title = Yii::t('app','Purchase');
$this->pageTitle=Yii::app()->name . ' - '.$title;
$this->breadcrumbs=array($title);

if(count($cart->items))
{
	$options = array(
			'title' => Yii::t('app','Proceed with Purchase'),
			'data' => $data,
			'cart' => $cart,
			'page_id' => $page_id,
			'actions' => utils::toAttributePropertiesArray($actions),
			'page_content' => utils::mustacheRender($template, $this->getId(), array(
				'data' => $data->getData(),
				'labels'=>$data->attributeLabels(),
				'vendorUrl'=> '/public',
			)),
			'errorSummary' => (isset($errors) && count($errors))? 
				utils::mustacheRender('errorSummary', 'documents', array(
					'errors' => utils::toAttributeArray($errors)
				)):false,
		);
	$this->mustacheRender('index', $this->getId(), $options);
}else	 
		$this->mustacheRender('cart/isEmpty', 'shopping', array(
			'title' => $title,
		));	
