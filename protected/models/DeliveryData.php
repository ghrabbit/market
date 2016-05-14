<?php

/**
 * CustomerData class.
 * CustomerData is the data structure for keeping
 * customer data in purchase form. 
 */
class DeliveryData extends WizzardData
{
	public $deliveryUsed;
	public $daddress;
	public $comments;

	public function init()
	{
		$this->deliveryUsed = 0;
	}
	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		$baseRules = array(
			array('deliveryUsed', 'required'),
			array('deliveryUsed', 'boolean'),
		);
		if($this->deliveryUsed)
		{
			$baseRules[] = array('daddress, comments', 'required');
			$baseRules[] = array('daddress, comments', 'length', 128);
		}	
		return $baseRules;
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'deliveryUsed'=>Yii::t('app','Delivery Used'),
			'daddress'=>Yii::t('app','Delivery Address'),
			'comments'=>Yii::t('app','Comments'),
		);
	}
	
}
