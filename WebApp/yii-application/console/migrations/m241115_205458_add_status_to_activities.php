<?php

use yii\db\Migration;

/**
 * Class m241115_205458_add_status_to_activities
 */
class m241115_205458_add_status_to_activities extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('activities', 'status', $this->integer()->notNull()->defaultValue('1'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241115_205458_add_status_to_activities cannot be reverted.\n";
        $this->dropColumn('activities', 'status');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241115_205458_add_status_to_activities cannot be reverted.\n";

        return false;
    }
    */
}
