<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%booking}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%activity}}`
 * - `{{%calendar}}`
 * - `{{%user}}`
 */
class m241027_181853_create_booking_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%booking}}', [
            'id' => $this->primaryKey(),
            'activity_id' => $this->integer()->notNull(),
            'calendar_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'numberpax' => $this->integer()->notNull(),
        ]);

        // creates index for column `activity_id`
        $this->createIndex(
            '{{%idx-booking-activity_id}}',
            '{{%booking}}',
            'activity_id'
        );

        // add foreign key for table `{{%activity}}`
        $this->addForeignKey(
            '{{%fk-booking-activity_id}}',
            '{{%booking}}',
            'activity_id',
            '{{%activity}}',
            'id',
            'CASCADE'
        );

        // creates index for column `calendar_id`
        $this->createIndex(
            '{{%idx-booking-calendar_id}}',
            '{{%booking}}',
            'calendar_id'
        );

        // add foreign key for table `{{%calendar}}`
        $this->addForeignKey(
            '{{%fk-booking-calendar_id}}',
            '{{%booking}}',
            'calendar_id',
            '{{%calendar}}',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-booking-user_id}}',
            '{{%booking}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-booking-user_id}}',
            '{{%booking}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%activity}}`
        $this->dropForeignKey(
            '{{%fk-booking-activity_id}}',
            '{{%booking}}'
        );

        // drops index for column `activity_id`
        $this->dropIndex(
            '{{%idx-booking-activity_id}}',
            '{{%booking}}'
        );

        // drops foreign key for table `{{%calendar}}`
        $this->dropForeignKey(
            '{{%fk-booking-calendar_id}}',
            '{{%booking}}'
        );

        // drops index for column `calendar_id`
        $this->dropIndex(
            '{{%idx-booking-calendar_id}}',
            '{{%booking}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-booking-user_id}}',
            '{{%booking}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-booking-user_id}}',
            '{{%booking}}'
        );

        $this->dropTable('{{%booking}}');
    }
}
