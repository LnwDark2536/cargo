<?php

use yii\db\Migration;

class m171117_080430_addordersAccountNumber extends Migration
{
    public function up()
    {
        $this->addColumn('orders', 'account_number', $this->string());
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171117_080430_addordersAccountNumber cannot be reverted.\n";

        return false;
    }
    */
}
