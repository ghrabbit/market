<?php
$this->layoutTemplate='admindex';
$data = array(
		'title' => $this->pageTitle,
		'username' => Yii::app()->user->name,
		'info'=>$info,
		'items' => $items,
		'labels'=>array_merge($model->attributeLabels(),array(
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
			
			'action'=>Yii::t('app','Action'),
			'category'=>Yii::t('app','Category'),
			'parent'=>Yii::t('app','Parent'),
			'description'=>Yii::t('app','Description'),
			'imageFile'=>Yii::t('app','Image file'),

			'add'=>Yii::t('app','Add'),
			'username'=>Yii::t('app','Username'),
			'empty'=>Yii::t('app','Empty list'),
		)),
);
$this->mustacheRender('index', $this->getId(), $data);
