<?php

class m160218_142512_create_users_table extends CDbMigration
{
	public function up()
	{
		$transaction=$this->getDbConnection()->beginTransaction();
		try
		{
			$this->createTable("users", array(
				'id' => 'pk',
				'username' => 'varchar(32) NOT NULL',
				'password' => 'varchar(64) NOT NULL',
				'priv' => 'varchar(5)',
				'firstname' => 'varchar(64) NOT NULL',
				'lastname' => 'varchar(64) NOT NULL',
				'email' => 'varchar(128) NOT NULL',
				'phone' => 'varchar(32)',
				'address' => 'text',
				'salt' => 'char(10) NOT NULL',
				'newpassword' => 'varchar(64)',
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
		$transaction=$this->getDbConnection()->beginTransaction();
		try
		{
			//echo "m160218_142512_create_users_table does not support migration down.\n";
			$this->dropTable('users');
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
	
	 '"'.$name.'"' 

	public function safeDown()
	{
	}
	*/
}
