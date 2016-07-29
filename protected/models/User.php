<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property string $phone
 * @property string $address
 * @property string $salt
 * @property string $newpassword
 * @property string $_roles
 */
class User extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, password, firstname, lastname, email', 'required'),
			array('username, phone', 'length', 'max'=>32),
			array('password, firstname, lastname', 'length', 'max'=>64),
			array('_roles', 'length', 'max'=>255),
			array('email', 'length', 'max'=>128),
			array('address', 'length', 'max'=>255),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, password, firstname, lastname, email, phone', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'username' => Yii::t('app','Username'),
			'password' => Yii::t('app','Password'),
			'role' => Yii::t('app','Role'),
			'roles' => Yii::t('app','Roles'),
			'firstname' => Yii::t('app','Firstname'),
			'lastname' => Yii::t('app','Lastname'),
			'email' => Yii::t('app','Email'),
			'phone' => Yii::t('app','Phone'),
			'address' => Yii::t('app','Address'),
			'id' => 'ID',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('firstname',$this->firstname,true);
		$criteria->compare('lastname',$this->lastname,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('phone',$this->phone,true);

		return new CActiveDataProvider('User', array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	function getRoles()
	{
		//return explode(",", $this->_roles);
		return preg_split("/[\s,]+/", $this->_roles);
	}
	
/*
 * 
 * name: validatePassword
 * @param $password
 * @return true if the user's password is valid
 * 
 */
	
	public function validatePassword($password) {
		return crypt($password, $this->salt) == $this->password;
	}

/*
 * 
 * name: current
 * @param
 * @return user that was found in database or null
 * 
 */
	static function current() {
		$name = Yii::app()->user->name;
		return User::model()->find('LOWER(username)=?',array(strtolower($name)));
	}
	
/*
 * 
 * name: is_logged_in
 * @param
 * @return
 * 
 */	
	public static function is_logged_in() {
		return !Yii::app()->user->isGuest;
	}
	
	
/*
 * this function checks to see if the user is logged in.  if not, it will show
 * the login screen before allowing the user to continue 
 * 
 * name: require_login
 * @param
 * @return
 * 
 */
	public static function require_login() {
		if (! self::is_logged_in()) {
			//save current url
			Yii::app()->user->returnUrl = Yii::app()->getRequest()->url;
			throw new CHttpException(401,  Yii::t('app','Require login!'));
		}
	}
	
/*
 * 
 * require_role
 * @param
 * @return
 * 
 */
	public function require_role($role) {
		if (!in_array($role, $this->getRoles())){
			throw new CHttpException(500, Yii::t('app','Insufficient Privileges!'));
		}
	}
	
/*
 * 
 * has_role
 * @param
 * @return
 * 
 * returns true if the user has the role $role
 */
public function has_role($role) 
{
	return in_array($role, $this->getRoles());
}


/*
 * 
 * name: emailOrUsername
 * @param
 * @return
 * 
 * returns user if the email address or username exists and null else
 */
	static function getByEmailOrUsername($str) {

		return User::model()->find('email=:str or username=:str', array(':str'=>$str));
	}
    
    public function fullname()
	{
		return $this->firstname.' '.$this->lastname;
	}


/*
 * 
 * name: email_exists
 * @param
 * @return
 * 
 * returns true the email address exists
 */
	static function getByEmail($email) {
		return null !== User::model()->find('email=:email', array(':email'=>$email));
	}
	
	
	static function username_exists($username) {
	/* returns the true if the username exists */
		return self::model()->find('username=:username', array(':username'=>$username)) !== null;
	}
	
	const SHOWSHORT = 32;
	public function addressShowShort()
	{
	  if (function_exists('mb_substr')) 
	    return  mb_strlen($this->address, '8bit') < self::SHOWSHORT ? CHtml::encode($this->address) :  mb_substr($this->address, 0, self::SHOWSHORT-1, Yii::app()->charset).'...';
      return strlen($this->address) < self::SHOWSHORT ? CHtml::encode($this->address) :  substr($this->address, 0, self::SHOWSHORT-1).'...'; 
	}
	

}
