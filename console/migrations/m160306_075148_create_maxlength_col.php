<?php

use yii\db\Schema;
use yii\db\Migration;

class m160306_075148_create_maxlength_col extends Migration
{
    public function up()
    {
        $this->addColumn('specification', 'max_length', $this->integer()->defaultValue(0));
    }

    public function down()
    {
        echo "m160306_075148_create_maxlength_col cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
