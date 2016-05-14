<?php

class TopNavbarHelper extends CFormModel
{
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'home'=>Yii::t('app','Home'),
			'login'=>Yii::t('app','Login'),
			'signup'=>Yii::t('app','Signup'),
			'forgotPassword'=>Yii::t('app','Forgot my password'),
			'logout'=>Yii::t('app','Logout'),
			'settings'=>Yii::t('app','Settings'),
			'password'=>Yii::t('app','Password Change'),
			'action'=>Yii::t('app','Action'),
			'brand'=>Yii::t('app', Yii::app()->name),//Yii::t('app','Yii EShop Sample'),
		);
	}
}
