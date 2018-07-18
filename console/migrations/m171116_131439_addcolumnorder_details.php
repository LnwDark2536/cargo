<?php

use yii\db\Migration;

class m171116_131439_addcolumnorder_details extends Migration
{
    public function up()
    {
        $this->addColumn('order_details', 'style', $this->string()->after('product_id'));
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171116_131439_addcolumnorder_details cannot be reverted.\n";

        return false;
    }
    */
}
