<?php

/*
 * OrderItem.php
 * 
 * Copyright 2014 Sergey Artukh <yxrabbit@yandex.ru>
 * 
 * 
 */
 

/**
 * OrderItem class.
 * OrderItem is the data structure for keeping
 * order item data.
 */
/**
 * This is the model class for table "order_items".
 *
 * The followings are the available columns in table 'order_items':
 * @property integer $order_id
 * @property integer $product_id
 * @property integer $qty
 */
 
class OrderItem extends CActiveRecord
{
  const TABLE_NAME = 'order_items';	
  
  public static function model($className= __CLASS__ )
  {
    return parent::model($className);
  }
  
  public function tableName()
  {
    return self::TABLE_NAME;
  }
  
  public function primaryKey()
  {
    return array('order_id','product_id');
  }
  
  	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'product'=>array(self::HAS_ONE, 'Product',	array('id'=>'product_id')),
		);
	}
	
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'qty'=>Yii::t('app','Qty'),
		);
	}
	
	public function total()
	{
		return $this->product->price*$this->qty;
	}
}
