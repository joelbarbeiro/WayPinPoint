<?php

use yii\db\Migration;

/**
 * Class m250107_191557_create_bookingid_table_ticket
 */
class m250107_191557_create_bookingid_table_ticket extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%ticket}}', 'booking_id', $this->integer()->notNull());

        // creates index for column `activity_id`
        $this->createIndex(
            '{{%idx-ticket-booking_id}}',
            '{{%ticket}}',
            'booking_id'
        );

        // add foreign key for table `{{%activity}}`
        $this->addForeignKey(
            '{{%fk-ticket-booking_id}}',
            '{{%ticket}}',
            'booking_id',
            '{{%booking}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m250107_191557_create_bookingid_table_ticket cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250107_191557_create_bookingid_table_ticket cannot be reverted.\n";

        return false;
    }
    */
}
