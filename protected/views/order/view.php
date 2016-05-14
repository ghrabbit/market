<?php
$this->layoutTemplate='admindex';

$data = array(
		'title' => $this->pageTitle,
		'username' => Yii::app()->user->name,
		'model'=>$model,
		'items' => $model->items,
		'errorSummary'=>count($model->errors)? utils::renderErrors($model->errors):false,
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
			'submit'=>Yii::t('app','submit'),
			'requiredFields'=>Yii::t('app','requiredFields'),
			'username'=>Yii::t('app','Username'),
			'customerInfo'=>Yii::t('app','Customer information'),
            'moneyUnit'=>utils::money_unit_char(),
		)),
);
$this->mustacheRender('view', $this->getId(), $data);

