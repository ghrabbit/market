<?php

class m160304_104833_create_categories_table extends CDbMigration
{
	public function up()
	{
		$transaction=$this->getDbConnection()->beginTransaction();
		try
		{
			$this->createTable("categories", array(
				'id' => 'pk',
				'parent_id' => 'integer',
				'title' => 'varchar(64) NOT NULL',
				'description' => 'text',
				'img_file' => 'varchar(128)',
			));
			$this->addForeignKey('categories_parent_fk', 'categories', 'parent_id', 'categories', 'id', NULL, NULL);
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
		//echo "m160304_104833_create_categories_table does not support migration down.\n";
		$transaction=$this->getDbConnection()->beginTransaction();
		try
		{
			$this->dropTable('categories');
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
