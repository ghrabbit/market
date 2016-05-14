<?php

/**
 * CatalogForm class.
 * CatalogForm is the data structure for keeping
 */
class CatalogForm extends CFormModel
{

	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 */
	public function attributeLabels()
	{
		return array(
			'catalog' => Yii::t('app','Catalog'),
			'title' => Yii::t('app','Catalog'),
		);
	}
}
