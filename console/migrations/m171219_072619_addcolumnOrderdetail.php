<?php

use yii\db\Migration;

class m171219_072619_addcolumnOrderdetail extends Migration
{

    public function up()
    {
        $this->addColumn('orders', 'tracking_number', $this->string());
        $this->addColumn('order_details', 'transport_name', $this->string());
        $this->addColumn('order_details', 'bags', $this->integer());
        $this->addColumn('order_details', 'details', $this->string());
    }

}
