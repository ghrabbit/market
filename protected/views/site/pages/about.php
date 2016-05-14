<?php
$title = Yii::t('app','About');
//$this->pageTitle=Yii::app()->name . $title;
$this->breadcrumbs=array($title);

$data = array(
		'pageTitle' => $title,
	);
	$this->mustacheRender('pages/about', $this->getId(), $data);
?>
