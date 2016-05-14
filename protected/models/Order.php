<?php

/**
 * This is the model class for table "orders".
 *
 * The followings are the available columns in table 'orders':
 * @property integer $id
 * @property string  $user_id
 * @property string  $ordered
 * @property string  $approved
 * @property string  $customer
 * @property string  $phone
 * @property string  $email
 * @property text    $address
 * @property double  $amount
 * @property integer $qty
 * @property string  $payment_system
 */
class Order extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Order the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'orders';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, customer, phone, email, address, amount, qty, payment_system', 'required'),
			array('amount', 'numerical'),
			array('user_id, qty', 'numerical', 'integerOnly'=>true),
			array('address', 'length', 'max'=>255),
			array('ordered, approved', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, customer, ordered, approved, amount, payment_system', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'items'=>array(self::HAS_MANY, 'OrderItem',	'order_id'),
			'itemsCount'=>array(self::STAT, 'OrderItem', 'order_id'),
			'user'=>array(self::BELONGS_TO, 'User',	'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('app','ID'),
			'user' => Yii::t('app','User'),
			'ordered' => Yii::t('app','Creating Date'),
			'approved' => Yii::t('app','Activating Data'),
			'ordered' => Yii::t('app','Creating Date'),
			'approved' => Yii::t('app','Activating Data'),
			'customer' => Yii::t('app','Customer'),
			'phone' => Yii::t('app','Phone'),
			'amount' => Yii::t('app','Amount'),
			'qty' => Yii::t('app','Quantity'),
			'payment_system'=>Yii::t('app','Payment System'),
			'total'=>Yii::t('app','Total'),
			'grand-total'=>Yii::t('app','Grand Total'),
			'status' => Yii::t('app','Status'),
			'price' => Yii::t('app','Price'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('ordered',$this->ordered,true);
		$criteria->compare('approved',$this->approved,true);
		$criteria->compare('customer',$this->customer,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('amount',$this->amount);
		$criteria->compare('qty',$this->qty);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/*Added*/

/*
 * 
 * name: create
 * @param $data array of page finished wizzard
 * @return Order object
 * this function save wizzard data into the database and then save the shopping cart
 * contents into the order_items table 
 */
	
	static function create($data) {

		$connection=Yii::app()->db; 
		
		$session = Yii::app()->session;
		$user = User::current();
		$cart = $session['cart'];
		/* save order information first */
		$_now = time();
		$order = new Order;
		$order->user_id=$user->id;
		$order->ordered=$_now;
		$order->customer=$data['customer']->customer;
		$order->email=$data['customer']->email;
		$order->phone=$data['customer']->phone;
		$order->address=$data['customer']->address;
		$order->amount=$cart->total;
		$order->qty=$cart->itemcount();
		$order->payment_system = $data['paymentSelect']->paySystem;
		if(!$order->save())
		{
			//return null;
			$str = '';
			$ers=$order->getErrors();
			foreach($ers as $attr=>$msg)
			{
				$str = $str.','.$attr.'[';
				foreach($msg as $val)
					$str = $str.$val;
				$str = $str.']';	
			}
			throw new CHttpException(200,  Yii::t('app','Unable save record. count='.count($ers).' Invalid attributes:',$str));
		}

		foreach ($cart->products2Items() as $item) 
		{
			//utils::debug_array($item->product->attributes);
			$sql = "
			INSERT INTO order_items (
				order_id, product_id, price, qty
			) VALUES (
			 {$order->id}
			,{$item->product->id}
			,{$item->product->price}
			,{$item->qty}
			)";
			$command=$connection->createCommand($sql);
			$command->execute();
			}
			$now = date('c');
			$sql = "
			INSERT INTO order_history (
				order_id, status, description, ordered
			) VALUES (
			 {$order->id}
			,1
			,'pre-order'
			,'{$now}'
			)";
		$command=$connection->createCommand($sql);
		$command->execute();
		
		return $order;
	}
	
	public function credate()
	{
		//return date('H:I F d, Y T',$this->ordered);
		return strftime('%H:%M %B %d, %Y %Z',$this->ordered);
	}
	
	public function apprdate()
	{
		if(isset($this->approved))
			return strftime('%H:%M %B %d, %Y %Z',$this->approved);
		return '';	
	}
	
}
