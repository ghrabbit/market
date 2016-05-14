<?php

/**
 * MailSummaryData class.
 * MailSummaryData is the data structure for keeping
 * customer data in purchase form. 
 */
class MailSummaryData extends WizzardData
{
	//public $email;
	//no inputs here

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			//array('email', 'required'),
			//array('email', 'length', 'max'=>128),
			//array('email', 'email'),
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
			'mailTitle'=>Yii::t('app','Send order data by email address'),
			'customer'=> $this->wizzard->getData('customer')->attributeLabels(),
			'paymentSelect'=> $this->wizzard->getData('paymentSelect')->attributeLabels(),
			'cart'=> Yii::app()->session['cart']->attributeLabels(),
		);
	}
	
	public function getData()
	{
		$ret=array();
		foreach(['customer','paymentSelect'] as $pageId)
		  $ret[$pageId] = $this->wizzard->getData($pageId);
		$ret['cart'] = Yii::app()->session['cart'];
		return $ret;
	}
	
}
