<?php

/**
 * ForgotPasswordForm class.
 * ForgotPasswordForm is the data structure for keeping
 * ForgotPassword form data. It is used by the 'forgotPassword' action of 'AccountController'.
 */
class ForgotPasswordForm extends CFormModel
{
	public $emailOrUsername;
	public $verifyCode;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			// emailOrUsername is required
			array('emailOrUsername', 'required'),
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
			'emailOrUsername'=>Yii::t('app','emailOrUsername'),
			
			'refreshCode' => Yii::t('app','Refresh Code'),
			'captchaHelp' => Yii::t('app','captchaHelp'),
			'submit' => Yii::t('app','Submit'),
			'requiredFields'=>Yii::t('app','requiredFields'),
		);
	}
}
