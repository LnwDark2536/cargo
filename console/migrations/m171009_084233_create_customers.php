<?php

use yii\db\Migration;

class m171009_084233_create_customers extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%customers}}', [
            'id' => $this->primaryKey(),
            'customer_code'=>$this->string(),
            'name' => $this->string(),
            'lastname' => $this->string(),
            'sex'=>$this->integer(),
            'email' => $this->string(),
            'id_card' => $this->string(),
            'phone' => $this->string(),
            'address' => $this->string(),
            'rate' => $this->integer(),
            'recommender'=>$this->string(),
            'user_id'=>$this->integer(),
            'created_at' => $this->timestamp(),
            'updated_at' => $this->timestamp(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%customers}}');
    }
}
