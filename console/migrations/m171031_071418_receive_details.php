<?php

use yii\db\Migration;

class m171031_071418_receive_details extends Migration
{
    public function up(){
        $tableOptions=null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        $this->createTable('{{%receive_details}}', [
            'id' => $this->primaryKey(),
            'order_id'=>$this->integer(),
            'product_id'=>$this->integer(),
            'receive_amount'=>$this->integer(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%receive_details}}');
    }

}
