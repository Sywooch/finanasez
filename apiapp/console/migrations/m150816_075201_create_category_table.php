<?php

use yii\db\Schema;
use yii\db\Migration;

class m150816_075201_create_category_table extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'user_id'=>$this->integer(11)->notNull(),
            'name'=>$this->string(255)->notNull(),
            'comment'=>$this->string(255)->defaultValue(null),
            'type'=>$this->string('3')->notNull()->defaultValue('out'),
        ], $tableOptions);

        $this->addForeignKey('fk_category2user', '{{%category}}', 'user_id',
            '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropForeignKey('fk_category2user', '{{%category}}');
        $this->dropTable('{{%category}}');
    }
}
