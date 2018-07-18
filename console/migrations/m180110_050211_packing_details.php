<?php

use yii\db\Migration;

class m180110_050211_packing_details extends Migration
{
    public function up()
    {
        $this->createTable('{{%packing_details}}', [
            'id' => $this->primaryKey(),
            'packing_id' => $this->integer(),
            'ctn_no' => $this->string(),
            'order_id' => $this->integer(),
            'od_id' => $this->integer(),
            'customers_id'=>$this->integer(),
            'quantity' => $this->decimal(6,2),
            'unit_price' => $this->decimal(6,2),
            'bags' => $this->decimal(6,2),
            'kg' => $this->decimal(6,2),
            'width' => $this->decimal(6,2),
            'length' => $this->decimal(6,2),
            'height' => $this->decimal(6,2),
        ]);
    }

    public function down()
    {
        $this->dropTable('{{%packing_details}}');
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
