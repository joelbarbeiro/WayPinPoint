<?php

use yii\db\Migration;

/**
 * Class m241115_205458_add_status_to_activity
 */
class m241115_205458_add_status_to_activity extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('activity', 'status', $this->integer()->notNull()->defaultValue('1'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241115_205458_add_status_to_activity cannot be reverted.\n";
        $this->dropColumn('activitiy', 'status');
    }
}
