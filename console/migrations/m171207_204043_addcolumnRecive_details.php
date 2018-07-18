<?php

use yii\db\Migration;

class m171207_204043_addcolumnRecive_details extends Migration
{
    public function up()
    {
        $this->addColumn('receive_details', 'order_details_id', $this->integer());
        $this->addColumn('weight_details', 'order_details_id', $this->integer());
    }


    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171207_204043_addcolumnRecive_details cannot be reverted.\n";

        return false;
    }
    */
}
