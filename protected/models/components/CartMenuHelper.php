<?php

class CartMenuHelper extends CFormModel
{
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'shopping'=>Yii::t('app','Shopping'),
			'shopping-'=>Yii::t('app','Shopping'),
			'shopping-cart'=>Yii::t('app','Shopping Cart'),
			'qty'=>Yii::t('app','Qty'),
			'total'=>Yii::t('app','Total'),
			'purchase-now'=>Yii::t('app','Purchase Now'),
		);
	}
	
	public function itemcount()
	{
		return isset(Yii::app()->session['cart']) ? Yii::app()->session['cart']->itemcount() : 0;
	}
	
	public function ftotal()
	{
		return Cart::ftotal();
	}
}
