<?php

use yii\db\Migration;

class m171103_063047_AddColumnOrders extends Migration
{

    public function up()
    {
        $this->addColumn('orders', 'total_weight', $this->string()->after('supplier_id'));
    }

}
