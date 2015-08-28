<?php

use yii\db\Schema;
use yii\db\Migration;

class m150816_165532_update_tables_add_timestamp_fields extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->addColumn('{{%bill}}', 'updated_at', $this->integer(11)->defaultValue(null));
        $this->addColumn('{{%bill}}', 'created_at', $this->integer(11)->defaultValue(null));
        $this->addColumn('{{%category}}', 'created_at', $this->integer(11)->defaultValue(null));
        $this->addColumn('{{%category}}', 'updated_at', $this->integer(11)->defaultValue(null));
        $this->addColumn('{{%operation}}', 'created_at', $this->integer(11)->defaultValue(null));
        $this->addColumn('{{%operation}}', 'updated_at', $this->integer(11)->defaultValue(null));
    }

    public function down()
    {
        $this->dropColumn('{{%bill}}', 'updated_at');
        $this->dropColumn('{{%bill}}', 'created_at');
        $this->dropColumn('{{%category}}', 'created_at');
        $this->dropColumn('{{%category}}', 'updated_at');
        $this->dropColumn('{{%operation}}', 'created_at');
        $this->dropColumn('{{%operation}}', 'updated_at');
    }
}
