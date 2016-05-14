<?php

/**
 * EMailData class.
 * EMailData is the data structure for keeping
 * customer data in purchase form. 
 */
class EMailData extends WizzardData
{
	public $email;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array('email', 'required'),
			//array('email', 'length', 'max'=>128),
			array('email', 'email'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'email'=>Yii::t('app','Email'),
			
			'step'=>Yii::t('app','Step'),
			'enter1'=>Yii::t('app','Enter EMail address'),
		);
	}
	
}
