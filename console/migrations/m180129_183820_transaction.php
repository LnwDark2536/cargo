<?php

use yii\db\Migration;
use yii\db\Schema;
class m180129_183820_transaction extends Migration
{
    public function up()
    {
        $this->createTable('{{%transactions}}', [
            'id' => $this->primaryKey(),
            'customer_id'=>$this->integer(),
            'status'=>$this->integer(),
            'account_id'=>$this->integer(),
            'order_id'=>$this->integer(),
            'packing_id'=>$this->integer(),
            'amount_thai'=>$this->decimal(10,2),
            'amount_money'=>$this->decimal(10,2),
            'details'=>$this->string(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ]);
    }

    public function down()
    {
        $this->dropTable('transactions');
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
