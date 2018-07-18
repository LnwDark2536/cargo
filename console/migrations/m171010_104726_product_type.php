<?php

use yii\db\Migration;

class m171010_104726__productType extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%product_type}}', [
            'id' => $this->primaryKey(),
            'type_code' => $this->string(),
            'description' => $this->string(),
            'chinese_description'=>$this->string(),
            'unit'=>$this->integer(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%product_type}}');
    }
}
