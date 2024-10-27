<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%bookings}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%activities}}`
 * - `{{%calendar}}`
 * - `{{%user}}`
 */
class m241027_181853_create_bookings_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%bookings}}', [
            'id' => $this->primaryKey(),
            'activities_id' => $this->integer()->notNull(),
            'calendar_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'numberpax' => $this->integer()->notNull(),
        ]);

        // creates index for column `activities_id`
        $this->createIndex(
            '{{%idx-bookings-activities_id}}',
            '{{%bookings}}',
            'activities_id'
        );

        // add foreign key for table `{{%activities}}`
        $this->addForeignKey(
            '{{%fk-bookings-activities_id}}',
            '{{%bookings}}',
            'activities_id',
            '{{%activities}}',
            'id',
            'CASCADE'
        );

        // creates index for column `calendar_id`
        $this->createIndex(
            '{{%idx-bookings-calendar_id}}',
            '{{%bookings}}',
            'calendar_id'
        );

        // add foreign key for table `{{%calendar}}`
        $this->addForeignKey(
            '{{%fk-bookings-calendar_id}}',
            '{{%bookings}}',
            'calendar_id',
            '{{%calendar}}',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-bookings-user_id}}',
            '{{%bookings}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-bookings-user_id}}',
            '{{%bookings}}',
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
        // drops foreign key for table `{{%activities}}`
        $this->dropForeignKey(
            '{{%fk-bookings-activities_id}}',
            '{{%bookings}}'
        );

        // drops index for column `activities_id`
        $this->dropIndex(
            '{{%idx-bookings-activities_id}}',
            '{{%bookings}}'
        );

        // drops foreign key for table `{{%calendar}}`
        $this->dropForeignKey(
            '{{%fk-bookings-calendar_id}}',
            '{{%bookings}}'
        );

        // drops index for column `calendar_id`
        $this->dropIndex(
            '{{%idx-bookings-calendar_id}}',
            '{{%bookings}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-bookings-user_id}}',
            '{{%bookings}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-bookings-user_id}}',
            '{{%bookings}}'
        );

        $this->dropTable('{{%bookings}}');
    }
}
