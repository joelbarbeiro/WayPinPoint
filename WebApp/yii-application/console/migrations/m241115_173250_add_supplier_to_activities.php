<?php

use yii\db\Migration;

/**
 * Class m241115_173250_add_supplier_to_activities
 */
class m241115_173250_add_supplier_to_activities extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('activities', 'user_id', $this->integer()->notNull());

        $this->addForeignKey(
            'fk-activities-user_id',
            'activities',
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
        $this->dropForeignKey('fk-activities-user_id', 'activities');
        $this->dropColumn('activities', 'supplier_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241115_173250_add_supplier_to_activities cannot be reverted.\n";

        return false;
    }
    */
}
