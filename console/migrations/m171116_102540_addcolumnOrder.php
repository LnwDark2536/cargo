<?php

use yii\db\Migration;

class m171116_102540_addcolumnOrder extends Migration
{


    public function up()
    {
        $this->addColumn('orders', 'payment', $this->smallInteger());
    }
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171116_102540_addcolumnOrder cannot be reverted.\n";

        return false;
    }
    */
}
