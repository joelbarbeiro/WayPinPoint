<?php

use yii\db\Migration;

/**
 * Class m241115_173528_add_status_to_calendar
 */
class m241115_173528_add_status_to_calendar extends Migration
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
        echo "m241115_173528_add_status_to_calendar cannot be reverted.\n";
        $this->dropColumn('calendar', 'status');

    }
}
