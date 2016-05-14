<?php

/**
 * CustomerData class.
 * CustomerData is the data structure for keeping
 * customer data in purchase form. 
 */
class CustomerData extends WizzardData
{
	public $customer;
	public $email;
	public $phone;
	public $address;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			array('customer, email, phone, address', 'required'),
			array('email', 'email'),
			array('phone', 'length', 'max'=>11),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'customer'=>Yii::t('app','Customer'),
			'email'=>Yii::t('app','Email'),
			'phone'=>Yii::t('app','Phone'),
			'address'=>Yii::t('app','Address'),
			
			'step'=>Yii::t('app','Step'),
			'enter1'=>Yii::t('app','Enter Billing Information'),
			'customer-info'=>Yii::t('app','Customer Information'),
		);
	}
	
}
