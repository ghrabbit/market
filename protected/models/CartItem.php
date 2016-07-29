<?php
/*
 * CartItem.php
 * 
 */

/**
 * CartItem class.
 * CartItem is the data structure for keeping
 * cart item data.
 */
class CartItem extends CFormModel
{
	public $qty;
	public $product;
	
	function id() 
	{
			return $this->product->id;
	}
	
	function title() 
	{
			return $this->product->title;
	}
	
	function img_file() 
	{
			return $this->product->img_file;
	}
	
	function price() 
	{
			return $this->product->price;
	}
    
    function fprice() 
	{
			return $this->product->fprice();
	}

	function total() 
	{
			return $this->product->price*$this->qty;
	}
    
    function ftotal() 
	{
      if(isset(Yii::app()->params['moneySign']))
        return '<i class="fa '.Yii::app()->params['moneySign'].'"></i> '.$this->total();
      return $this->total(); 
	}
	
	function __asString() 
	{
			return $this->product->price*$this->qty;
	}
	
	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('qty', 'required'),
			// email has to be a valid email address
			array('qty', 'numerical'),
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
			///'name'=>Yii::t('app','Product Name'),
			///'price'=>Yii::t('app','Price'),
			'qty'=>Yii::t('app','Qty'),
			///'total'=>Yii::t('app','Total'),
		);
	}
}
