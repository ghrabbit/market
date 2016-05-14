<?php
$this->layoutTemplate='admindex';

$data = array(
		'title' => $this->pageTitle,
		'username' => Yii::app()->user->name,
		'model'=>$model,
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
		)),
		'categoryTree' => $model->isTop()?'':$this->widget('CategoryTree', array(
				'widgetId'=>'parentTree',
				'excluded'=>array($model->id), 
				'selectedCategories'=>isset($model->parent)?array($model->parent):array(),
				'selectMode'=>1),true),
		'formName'=>'entryform',
		'formId'=>'category-edit-form',
		'action'=>$this->createUrl($this->route,$this->actionParams),		
);
$this->mustacheRender('edit', $this->getId(), $data);

