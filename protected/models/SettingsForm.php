<?php

/**
 * SettingsForm class.
 * SettingsForm is the data structure for keeping
 * settings form data. It is used by the 'settings' action of 'AccountController'.
 */
class SettingsForm extends CFormModel
{
	public $username;
	public $email;
	public $firstname;
	public $lastname;
	//without password
	//public $password;
	public $phone;
	public $address;
	public $verifyCode;
	//do not touch salt and newpassword
	//public $salt, $newpassword;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			//
			array('username, email, firstname, lastname', 'required'),
			// email has to be a valid email address
			array('email', 'email'),
			array('phone', 'length', 'max'=>11),
			array('address', 'length', 'max'=>255),
			// verifyCode needs to be entered correctly
			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
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
			'verifyCode'=>Yii::t('app','Verification Code'),
			'username'=>Yii::t('app','Username'),
			'firstname'=>Yii::t('app','Firstname'),
			'lastname'=>Yii::t('app','Lastname'),
			'email'=>Yii::t('app','Email'),
			'phone' => Yii::t('app','Phone'),
			'address' => Yii::t('app','Address'),
			'refreshCode' => Yii::t('app','Refresh Code'),
			'captchaHelp' => Yii::t('app','captchaHelp'),
			'submit' => Yii::t('app','Submit'),
			'requiredFields'=>Yii::t('app','requiredFields'),
		);
	}
	
}
