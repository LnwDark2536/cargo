<?php

use yii\db\Migration;

class m171128_121605_addordersAccountName extends Migration
{
    public function up()
    {
        $this->addColumn('orders', 'account_name', $this->string());
        $this->alterColumn('orders','bank','varchar USING CAST(bank AS varchar)');
    }
    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171128_121605_addordersAccountName cannot be reverted.\n";

        return false;
    }
    */
}
