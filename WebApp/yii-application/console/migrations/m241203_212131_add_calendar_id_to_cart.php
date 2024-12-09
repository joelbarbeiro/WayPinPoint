<?php

use yii\db\Migration;

/**
 * Class m241203_212131_add_calendar_id_to_cart
 */
class m241203_212131_add_calendar_id_to_cart extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%cart}}', 'calendar_id', $this->integer()->notNull());

        $this->addForeignKey(
            'fk-cart-calendar_id',
            '{{%cart}}',
            'calendar_id',
            '{{%calendar}}',
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
        $this->dropForeignKey(
            'fk-cart-calendar_id',
            '{{%cart}}'
        );

        $this->dropColumn('{{%cart}}', 'calendar_id');
        echo "m241203_212131_add_calendar_id_to_cart cannot be reverted.\n";
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241203_212131_add_calendar_id_to_cart cannot be reverted.\n";

        return false;
    }
    */
}
