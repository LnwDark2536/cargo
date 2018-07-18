<?php

use yii\db\Migration;

/**
 * Class m171113_180248_alter_orders
 */
class m171113_180248_alter_orders extends Migration
{


    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $this->alterColumn('orders','deposit','numeric');
        $this->alterColumn('orders','bank','numeric USING CAST(bank AS numeric)');
    }
    /*
      public function down()
      {
          echo "m171113_180248_alter_orders cannot be reverted.\n";

          return false;
      }
      */
}
