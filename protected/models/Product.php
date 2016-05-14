<?php

/**
 * This is the model class for table "products".
 *
 * The followings are the available columns in table 'products':
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string img_file
 * @property double $price
 * @property integer $on_special
 */
class Product extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Product the static model class
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
		return 'products';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, price', 'required'),
			array('on_special', 'numerical', 'integerOnly'=>true),
			array('price', 'numerical'),
			array('title', 'length', 'max'=>32),
			array('description', 'length', 'max'=>255),
			array('img_file', 'length', 'max'=>128),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, price, on_special', 'safe', 'on'=>'search'),
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
			'categories'=>array(self::MANY_MANY, 'Category',	'products_categories(product_id, category_id)'),
			'categoriesCount'=>array(self::STAT, 'Category',	'products_categories(product_id, category_id)'),
		);
	}
	/**
	 * @return true if relational categoriesCount > 0.
	 */
	
	public function hasCategories()
	{
		return $this->categoriesCount > 0;
	}
		

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('app','ID'),
			'title' => Yii::t('app','Title'),
			'description' => Yii::t('app','Description'),
			'img_file' => Yii::t('app','Image'),
			'price' => Yii::t('app','Price'),
			'on_special' => Yii::t('app','On Special'),
			'categories' => Yii::t('app','Categories'),
			'productes' => Yii::t('app','Productes'),

			'productDetails' => Yii::t('app','Product Details'),
			'addToCart' => Yii::t('app','Add to Cart'),
			'image' => Yii::t('app','Image'),
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('price',$this->price);
		$criteria->compare('on_special',$this->on_special);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	public function get_on_specials() {
		/* print a list of categories that are on special */
	
		$criteria=new CDbCriteria;
		$criteria->compare('on_special', 1);
		$result = new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	
		return $result->getData();
	}
	
	public function get_on_specials_page($pageNo = 1, $pageSize = 0) {
		
		$result=new CActiveDataProvider($this, array(
			'criteria'=>array(
				'condition'=>'on_special=1',
				//'offset' => ($pageNo-1) * $pageSize,
				//'limit' => $pageSize
			),
			'pagination'=>array(
				'currentPage' => $pageNo - 1,
				'pageSize'=>$pageSize,
			),
		));

		return $result->getData();
	}
	
	public function get_on_specials_total_count() {
		/* print a list of categories that are on special */

		$criteria=new CDbCriteria;
		$criteria->compare('on_special', 1);
		$result = new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
		
		return $result->getTotalItemCount();
	}
	
	/**
	 * This is invoked after the record is deleted.
	 */
	protected function beforeDelete()
	{
		parent::beforeDelete();
		$this->removeRelations();
	}
	
	public function removeRelations()//$id, $parent_id)
	{
		$connection=Yii::app()->db;
		$command=$connection->createCommand();
		// delete this product from the products_categories table
		$command->delete('products_categories','product_id =:id', 
			array('id'=>$this->id));
	}
	
	function saveCarefully(array $categoryIds)
	{
		$connection=Yii::app()->db; 
		$transaction=$connection->beginTransaction();
		try
		{
			$this->save();
			// delete all the categories this product was associated with 
			$sql = "DELETE FROM products_categories
				WHERE product_id = '$this->id'";
			$command=$connection->createCommand($sql);
			$command->execute();

			// add associations for all the categories this product belongs to, if
			// no categories were selected, we will make it belong to the top
			// category 
			if (!isset($categoryIds) || (count($categoryIds) == 0)) 
			{
				$categoryIds[] = 0;
			}

			$sql = "INSERT INTO products_categories ( 
					product_id,category_id) VALUES ('$this->id', :cat_id )";
					
			$command=$connection->createCommand($sql);
			for ($i = 0; $i < count($categoryIds); $i++) {
					//Yii::log("TRY SAVE <'$this->id':'{$categoryIds[$i]}'>",'warning');
					$command->bindParam('cat_id', $categoryIds[$i]);
					$command->execute();
					//Yii::log("WAS SAVED <'$this->id':'{$categoryIds[$i]}'>",'warning');
			}
				
			$transaction->commit();
		}
		catch(Exception $e) // an exception is raised if a query fails
		{
			$transaction->rollBack();
			//Yii::log("WAS Rolled BACK <'$this->id'> Error:".$e->getMessage(),'warning');
		}	
		
	}
	
	const SHOWSHORT = 32;
	public function descriptionShowShort()
	{
	  if (function_exists('mb_substr')) 
	    return  mb_strlen($this->description, '8bit') < self::SHOWSHORT ? CHtml::encode($this->description) :  mb_substr($this->description, 0, self::SHOWSHORT-1, Yii::app()->charset).'...';
      return strlen($this->description) < self::SHOWSHORT ? CHtml::encode($this->description) :  substr($this->description, 0, self::SHOWSHORT-1).'...'; 
	}
}
