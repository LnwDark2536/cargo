<?php

use yii\db\Migration;

class m171012_065746_Orders extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%orders}}', [
            'id' => $this->primaryKey(),
            'invoice_id'=>$this->string(),
            'status'=>$this->integer(),
            'type_order'=>$this->integer(),
            'customers_id'=>$this->integer(),
            'supplier_id'=>$this->integer(),
            'date_order'=>$this->date(),
            'phone'=>$this->string(),
            'deposit'=>$this->float(),
            'payment'=>$this->smallInteger(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%orders}}');
    }
}
