<?php

use yii\db\Migration;

class m171116_080028_alterPhoneOrder extends Migration
{



    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->alterColumn('orders','phone','varchar USING CAST(phone AS varchar)');
    }
/*
    public function down()
    {
        echo "m171116_080028_alterPhoneOrder cannot be reverted.\n";

        return false;
    }
    */
}
