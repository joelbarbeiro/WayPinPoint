<?php

use yii\db\Migration;

/**
 * Class m241203_232204_add_booking_to_invoice
 */
class m241203_232204_add_booking_to_invoice extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%invoice}}', 'booking_id', $this->integer()->notNull());

        $this->addForeignKey(
            'fk-invoice-booking_id',
            '{{%invoice}}',
            'booking_id',
            '{{%booking}}',
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
            'fk-invoice-booking_id',
            '{{%invoice}}'
        );

        $this->dropColumn('{{%invoice}}', 'booking_id');
        echo "m241203_232204_add_booking_to_invoice cannot be reverted.\n";
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241203_232204_add_booking_to_invoice cannot be reverted.\n";

        return false;
    }
    */
}
