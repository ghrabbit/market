<?php
/* @var $this SiteController */
	
	$this->pageTitle = Yii::t('app','Error');
	$data = array(
		'code'=>$code,
		'message'=>$message,
		'errorComment'=>Yii::t('app','What is the matter'),
		'labels'=> array(
			'errorPrompt'=>Yii::t('app','There is unexpected thing!'),
			'code'=>Yii::t('app','Code')
		),
	);
	$this->mustacheRender('pages/error', $this->getId(), $data);
?>
