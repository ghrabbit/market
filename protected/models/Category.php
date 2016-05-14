<?php

/**
 * This is the model class for table "categories".
 *
 * The followings are the available columns in table 'categories':
 * @property integer $id
 * @property integer $parent_id
 * @property string $title
 * @property string $description
 * @property string img_file
 */
class Category extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Category the static model class
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
		return 'categories';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title', 'required'),
			array('parent_id', 'numerical', 'integerOnly'=>true),
			array('title', 'length', 'max'=>64),
			array('description', 'length', 'max'=>255),
			array('img_file', 'length', 'max'=>128),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, parent_id, title', 'safe', 'on'=>'search'),
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
			'products'=>array(self::MANY_MANY, 'Product',	'products_categories(category_id, product_id)'),
			'productsCount'=>array(self::STAT, 'Product',	'products_categories(category_id, product_id)'),
			'subCategories'=>array(self::HAS_MANY, 'Category',	'parent_id'),
			'subCategoriesCount'=>array(self::STAT, 'Category',	'parent_id'),
			'parent'=>array(self::BELONGS_TO, 'Category',	'parent_id'),
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('app','ID'),
			'parent_id' => Yii::t('app','Parent'),
			'title' => Yii::t('app','Title'),
			'description' => Yii::t('app','Description'),
			'img_file' => Yii::t('app','Image'),
			'subCategories' => Yii::t('app','Subcategories'),
			'products' => Yii::t('app','Products'),
			'catalog' => Yii::t('app','Catalog'),
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
		$criteria->compare('parent_id',$this->parent_id);
		$criteria->compare('title',$this->title,true);
		//$criteria->compare('description',$this->description,true);
		//$criteria->compare('img_file',$this->img_file,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	function isTop()
	{
		return $this->id === 0;
	}
	
	function hasParent()
	{
		return isset($this->parent_id) and $this->parent_id;
	}
	
	static public function get_category_path($id=0) {
	/* returns a tree of the product categories, starting from the top to the
	 * category specified by $id */
		$tableName = self::model()->tableName();
		
		$sql = "SELECT parent_id, title FROM $tableName 
		WHERE id = $id";
		
		$connection=Yii::app()->db; 
		$command=$connection->createCommand($sql);
		$reader = $command->query();
		$ret = array();
		if(($row = $reader->read()) != false) {

			$parent = (integer)$row['parent_id'];
			$title = $row['title'];
			$url = Yii::app()->homeUrl."/catalog/index/$id";
			$ret[$row['title']] = $url;
		} else {
				$parent = 0;
				$title = "";
				$url = "";
		}
		
		if ($parent > 0) {	
			$ret = self::get_category_path($parent);
			$ret[$title] = $url;

		} elseif ($id > 0) {
			return array(Yii::t('app','Top') => Yii::app()->homeUrl."/catalog/index",	$title => $url);
		} elseif ($id == NULL) {
			return array(Yii::t('app','Top') => Yii::app()->homeUrl."/catalog/index");
		}
		return $ret;
	}
	

	/**
	 * This is invoked after the record is deleted.
	 */
	protected function afterDelete()
	{
		parent::afterDelete();
		$this->removeRelations();//$this->id, $this->parent_id);
	}
	
	public function removeRelations()//$id, $parent_id)
	{
		$connection=Yii::app()->db;
		$command=$connection->createCommand();
		// re-assign all the products in this category to the parent category 
		$command->update('products_categories',
			array('category_id' => isset($this->parent_id)?$this->parent_id:0), 
			/*where*/'category_id =:id', 
			array('id'=>$this->id));
		// re-assign all sub categories of this category to the parent category
		$command->update('categories',
			array('parent_id' => $this->parent_id),  
			/*where*/'parent_id =:id', 
			array('id'=>$this->id));
	}
	
	public function getSubCategoriesPage($pageNo = 1, $pageSize = 0) {
		
		
		$result=new CActiveDataProvider($this, array(
			'criteria'=>array(
				'condition'=>'parent_id = '.$this->id,
			),
			
			'pagination'=>array(
				'currentPage' => $pageNo - 1,
				'pageSize'=>$pageSize,
			),
			
		));

		return $result->getData();
	}
	
	public function getSubCategoriesTotalCount() {
		/* print a list of categories that are on special */

		
		$criteria=new CDbCriteria;

		$criteria->compare('parent_id', $this->id);

		$result = new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
		
		return $result->getTotalItemCount();
	
	}
	
	public function __toString() {
        return $this->name;
    }
	
	public function getProductsPage($pageNo = 1, $pageSize = 0) 
	{
		return $this->getRelated('products',true,array(
			'offset' => ($pageNo - 1)*$pageSize,
			'limit'=>$pageSize,
		));
	}
	
	public function getProductsTotalCount() {
		return $this->productsCount;
	}
	

}
