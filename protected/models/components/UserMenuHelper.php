<?php

class UserMenuHelper extends CFormModel
{
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'actions'=>Yii::t('app','Customer actions'),
			'catalog'=>Yii::t('app','Catalog'),
			'logout'=>Yii::t('app','Logout'),
			'settings'=>Yii::t('app','Settings'),
			'password'=>Yii::t('app','Password'),
			'login'=>Yii::t('app','Login'),
			'signup'=>Yii::t('app','Signup'),
			'about'=>Yii::t('app','About'),
			'contact'=>Yii::t('app','Contact'),
		);
	}
}
