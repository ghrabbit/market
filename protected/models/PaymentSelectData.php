<?php

/**
 * PaymentData class.
 * PaymentData is the data structure for keeping
 * payment data in purchase form. 
 */
class PaymentSelectData extends WizzardData
{
	const EMAIL = "email";
	const CCARD = "ccard";
	const WALLET = "wallet";
	
	public $paySystem = self::EMAIL;
	

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('paySystem', 'required'),
			//array('paySystem', 'length', 'max'<=64),
		);
	}

	/**	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'paySystem'=>Yii::t('app','Payment Kind'),
			
			'step'=>Yii::t('app','Step'),
			'select1'=>Yii::t('app','Select payment system'),
			'selected'=>Yii::t('app','Selected payment system'),
			'paysys'=>Yii::t('app','Payment System'),
			self::EMAIL=>Yii::t('app','Email'),
			self::CCARD=>Yii::t('app','Credit card'),
			self::WALLET=>Yii::t('app','Wallet'),
		);
	}
	
	public function getTitle()
	{
		return $this->attributeLabels()[$this->paySystem];	
	}

	public function paymentKindValues()
	{
		return array( 
			self::EMAIL=>Yii::t('app','just send me message by mail'),	
			"ccard"=>Yii::t('app','use credit card'),
			"wallet"=>Yii::t('app','use wallet'), 
		);
	}
	
	public function paymentKinds()
	{
		$ret = array();
		foreach($this->paymentKindValues() as $key=>$value )
		{
			$ret[] = array('attribute'=>$key, 'value'=>$value, 'isSelected'=>($this->paySystem === $key) );
		}
		return $ret;
	}
	
	
}
