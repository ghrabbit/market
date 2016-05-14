<?php
$this->layoutTemplate='admindex';
$data = array(
		'title' => $this->pageTitle,
		'username' => Yii::app()->user->name,
		'info'=>$info,
		'items' => $items,
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
			
			'Action'=>Yii::t('app','Action'),
			'Category'=>Yii::t('app','Category'),
			'Parent'=>Yii::t('app','Parent'),
			'Description'=>Yii::t('app','Description'),
			'ImageFile'=>Yii::t('app','Image file'),

			'Add'=>Yii::t('app','Add'),
		),
);
$this->mustacheRender('index', $this->getId(), $data);
