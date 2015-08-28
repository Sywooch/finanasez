<?php

use yii\db\Schema;
use yii\db\Migration;

class m150816_075619_create_operation_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%operation}}', [
            'id' => $this->primaryKey(),
            'user_id'=>$this->integer(11)->notNull(),
            'category_id'=>$this->integer(11)->notNull(),
            'bill_id'=>$this->integer(11)->notNull(),
            'amount'=>$this->money(17,2)->notNull(),
            'comment'=>$this->string(255)->defaultValue(null),
        ], $tableOptions);

        $this->addForeignKey('fk_operation2user', '{{%operation}}', 'user_id',
            '{{%user}}', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_operation2bill', '{{%operation}}', 'bill_id',
            '{{%bill}}', 'id', 'RESTRICT', 'CASCADE');
        $this->addForeignKey('fk_operation2category', '{{%operation}}', 'category_id',
            '{{%category}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_operation2user', '{{%operation}}');
        $this->dropTable('{{%operation}}');
    }
}
