<?php

/**
 * PasswordForm class.
 * PasswordForm is the data structure for keeping
 */
class PasswordForm extends CFormModel
{
	public $password;
	public $newPassword;
	public $confirmPassword;
	public $verifyCode;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('newPassword, password, confirmPassword', 'required'),
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
			'newPassword'=>Yii::t('app','New password'),
			'password'=>Yii::t('app','Password'),
			'confirmPassword'=>Yii::t('app','Confirm password'),
			'refreshCode' => Yii::t('app','Refresh Code'),
			'captchaHelp' => Yii::t('app','captchaHelp'),
			'submit' => Yii::t('app','Submit'),
			'requiredFields'=>Yii::t('app','requiredFields'),
		);
	}
}
