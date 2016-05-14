<?php
$this->layoutTemplate='admindex';

$data = array(
		'title' => $this->pageTitle,
		'username' => Yii::app()->user->name,
		/*
		'pageItems' => $items,
		'pager' => $this->widget('Pager', array('pages' => $pages,), true),
		'itemsCount' => count($items),
		'pageNo'=> $pageNo,
		'pageSize' => $pageSize,
		*/
		'labels'=>array(
			'home'=>Yii::t('app','Home'),
			'login'=>Yii::t('app','Login'),
			'logout'=>Yii::t('app','Logout'),
			'settings'=>Yii::t('app','Settings'),
			'admin'=>Yii::t('app','Admin'),
			'adminHome'=>Yii::t('app','Admin Home'),
			'operations'=>Yii::t('app','Operations'),
			'category'=>Yii::t('app','Category'),
			'product'=>Yii::t('app','Product'),
			'user'=>Yii::t('app','User'),
			'order'=>Yii::t('app','Order'),
		),
);
$this->mustacheRender('index', $this->getId(), $data);
?>



