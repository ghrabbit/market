<?php
/* @var $this ShoppingController */

	$this->pageTitle=Yii::app()->name.' - '. $title;
	$model = Yii::app()->session['cart'];	
	$products = $model->itemsPage($pageNo, 6);
	//Yii::log('product count on page='.count($products),'warning');
 		
	$criteria = new CDbCriteria();
	$count=count($model->items);
	$pages=new CPagination($count);

	// results per page
	$pages->pageSize=6;
	$pages->applyLimit($criteria);
	$pages->params=array('page'=>$pageNo);

	$labels = $model->attributeLabels();
	$items = array();
	/**/
	//if($products)
	if(is_array($products))
	foreach($products as $one)
	{
		$items[] = array('model'=>$one, 'labels'=>$labels);
	}	
	
	if(count($items))
	{	
		$data = array(
			'title' => $title,
			//'username' => Yii::app()->user->name,
			//'authenticated'=>User::is_logged_in(),
			'pageItems' => $items,
			'pager' => $this->widget('Pager', array('pages' => $pages,), true),
			'itemsCount' => count($items),
			'pageNo'=> $pageNo,
			'pageSize' => $pageSize,
			'labels'=>$labels,
			'model'=>$model,
		);
		$this->mustacheRender('index', $this->getId(), $data);
	}else	 
		$this->mustacheRender('cart/isEmpty', $this->getId(), array(
			'title' => $title,
		));
?>
