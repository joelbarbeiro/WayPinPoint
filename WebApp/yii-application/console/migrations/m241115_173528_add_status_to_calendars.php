<?php

use yii\db\Migration;

/**
 * Class m241115_173528_add_status_to_calendars
 */
class m241115_173528_add_status_to_calendars extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('calendar', 'status', $this->integer()->notNull()->defaultValue('1'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241115_173528_add_status_to_calendars cannot be reverted.\n";
        $this->dropColumn('calendar', 'status');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241115_173528_add_status_to_calendars cannot be reverted.\n";

        return false;
    }
    */
}
