<?php

class m160304_104452_create_products_table extends CDbMigration
{
	public function up()
	{
		$transaction=$this->getDbConnection()->beginTransaction();
		try
		{
			$this->createTable("products", array(
				'id' => 'pk',
				'title' => 'varchar(64) NOT NULL',
				'description' => 'text',
				'price' => 'float',
				'on_special' => 'varchar(1) NOT NULL default 0',
				'img_file' => 'varchar(128)',
			));
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
		//echo "m160304_104452_create_products_table does not support migration down.\n";
		$transaction=$this->getDbConnection()->beginTransaction();
		try
		{
			$this->dropTable('products');
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
