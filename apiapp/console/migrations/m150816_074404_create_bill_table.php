<?php

use yii\db\Schema;
use yii\db\Migration;

class m150816_074404_create_bill_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bill}}', [
            'id' => $this->primaryKey(),
            'user_id'=>$this->integer(11)->notNull(),
            'name'=>$this->string(255)->notNull(),
            'money'=>$this->money(17, 2)->notNull()->defaultValue(0)
        ], $tableOptions);

        $this->addForeignKey('fk_bill2user', '{{%bill}}', 'user_id',
        '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_bill2user', '{{%bill}}');
        $this->dropTable('{{%bill}}');
    }
}
