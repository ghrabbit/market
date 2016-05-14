<?php
$title = Yii::t('app','Purchase');
$this->pageTitle=Yii::app()->name . ' - '.$title;
$this->breadcrumbs=array($title);

	 
		$this->mustacheRender('mail/success', 'purchase', array(
			'title' => $title,
		));	
