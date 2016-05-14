<?php

class m160304_111909_create_products_categories_table extends CDbMigration
{
	public function up()
	{
		$transaction=$this->getDbConnection()->beginTransaction();
		try
		{
			$this->createTable("products_categories", array(
				'product_id' => 'integer',
				'category_id' => 'integer',
			));
			//addForeignKey(string $name, string $table, string $columns, string $refTable, string $refColumns, string $delete=NULL, string $update=NULL)
			$this->addForeignKey('products_categories_product_fk', 'products_categories', 'product_id', 'products', 'id', NULL, NULL);
			$this->addForeignKey('products_categories_category_fk', 'products_categories', 'category_id', 'categories', 'id', NULL, NULL);
			//createIndex(string $name, string $table, string $column, boolean $unique=false)
			$this->createIndex( 'products_categories_ui_0', 'products_categories', 'product_id, category_id', true);
			$transaction->commit();
		}
		catch(Exception $e)
		{
			echo "Exception: ".$e->getMessage()."\n";
			$transaction->rollBack();
			return false;
		}
		return true;
	}

	public function down()
	{
		//echo "m160304_111909_create_products_categories_table does not support migration down.\n";
		$transaction=$this->getDbConnection()->beginTransaction();
		try
		{
			$this->dropTable('products_categories');
			$transaction->commit();
		}
		catch(Exception $e)
		{
			echo "Exception: ".$e->getMessage()."\n";
			$transaction->rollBack();
			return false;
		}
		return true;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}
