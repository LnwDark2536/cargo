<?php

use yii\db\Migration;

class m171220_175544_addcolumnOrderdetailBags extends Migration
{
    public function up()
    {
        $this->addColumn('order_details', 'bags', $this->integer());
        $this->addColumn('orders', 'bill_number', $this->string());
        $this->addColumn('orders', 'order_bags', $this->integer());
    }
}
