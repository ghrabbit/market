<?php
/* @var $this SiteController */

	$this->pageTitle=Yii::app()->name.' - '. $title;
	$model = Product::model();
	$count=$model->get_on_specials_total_count();
	$pages=new CPagination($count);
	//to avoid $_GET assign
	$pages->params = array();
	$criteria = new CDbCriteria();
	$pages->pageSize=$pageSize;
	$pages->applyLimit($criteria);
	/*the items on given page*/
	$_items = $model->get_on_specials_page($pageNo, $pageSize); 	
		
	$labels = $model->attributeLabels();
	$items = array();
	/**/
	foreach($_items as $one)
	{
		$items[] = array('model'=>$one, 'labels'=>$labels);
	}	
		
	$data = array(
		'title' => $title,
		'username' => Yii::app()->user->name,
		'pageItems' => $items,
		'pager' => $this->widget('Pager', array('pages' => $pages,), true),
		'itemsCount' => count($items),
		'pageNo'=> $pageNo,
		'pageSize' => $pageSize,
	);
	$this->mustacheRender('index', $this->getId(), $data);
?>
