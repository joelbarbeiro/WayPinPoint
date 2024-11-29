<?php

use yii\db\Migration;

/**
 * Class m241128_211106_add_category_to_activity
 */
class m241128_211106_add_category_to_activity extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('activity', 'category_id', $this->integer()->notNull());

        $this->addForeignKey(
            'fk-activity-category_id',
            'activity',
            'category_id',
            'category',
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
        $this->dropForeignKey('fk-activity-category_id','activity');
        $this->dropColumn('category_id','activity');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241128_211106_add_category_to_activity cannot be reverted.\n";

        return false;
    }
    */
}
