<?php

/**
 * NotImplementedData class.
 * NotImplementedData is the data structure for keeping
 * customer data in purchase form. 
 */
class NotImplementedData extends WizzardData
{
	//no inputs here

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array();
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array();
	}
	
}
