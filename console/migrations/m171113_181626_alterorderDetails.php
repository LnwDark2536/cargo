<?php

use yii\db\Migration;

/**
 * Class m171113_181626_alterorderDetails
 */
class m171113_181626_alterorderDetails extends Migration
{
    public function up()
    {
        $this->alterColumn('order_details','quantity','numeric USING CAST(quantity AS numeric)');
        $this->alterColumn('order_details','unit_price','numeric USING CAST(unit_price AS numeric)');
    }
}
