<?php

use yii\db\Migration;

/**
 * Class m241115_173250_add_supplier_to_activity
 */
class m241115_173250_add_supplier_to_activity extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('activity', 'user_id', $this->integer()->notNull());

        $this->addForeignKey(
            'fk-activity-user_id',
            'activity',
            'user_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-activity-user_id', 'activity');
        $this->dropColumn('activity', 'supplier_id');
    }
}
