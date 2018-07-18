<?php

use yii\db\Migration;

class m180202_101949_account extends Migration
{
    public function up()
    {
        $this->createTable('{{%account}}', [
            'id'=>$this->primaryKey(),
            'status'=>$this->integer(),
            'name_account'=>$this->string(),
            'name_bank'=>$this->string(),
            'number_bank'=>$this->string(),
            'details'=>$this->string(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('account');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
