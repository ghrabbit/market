<?php
$this->pageTitle=Yii::app()->name.' - ' . $title;
$this->breadcrumbs=array($title);

//$options = array();
$this->mustacheRender($template, 'account', $options);
?>
