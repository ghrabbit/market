<?php

/**
 * SignupForm class.
 * SignupForm is the data structure for keeping
 * signup form data. It is used by the 'signup' action of 'AccountController'.
 */
class SignupForm extends CFormModel
{
	public $username;
	public $email;
	public $firstname;
	public $lastname;
	public $password;
	public $phone;
	public $address;
	public $verifyCode;
	

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// name, email, subject and body are required
			array('username, email, firstname, lastname, password', 'required'),
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
			'password'=>Yii::t('app','Password'),
			'phone' => Yii::t('app','Phone'),
			'address' => Yii::t('app','Address'),
			//
			'refreshCode' => Yii::t('app','Refresh Code'),
			'captchaHelp' => Yii::t('app','captchaHelp'),
			'submit' => Yii::t('app','Submit'),
			'requiredFields'=>Yii::t('app','requiredFields'),
		);
	}
}
