<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%calendar}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%activity}}`
 * - `{{%dates}}`
 * - `{{%times}}`
 */
class m241027_181850_create_calendar_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%calendar}}', [
            'id' => $this->primaryKey(),
            'activity_id' => $this->integer()->notNull(),
            'date_id' => $this->integer()->notNull(),
            'time_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `activity_id`
        $this->createIndex(
            '{{%idx-calendar-activity_id}}',
            '{{%calendar}}',
            'activity_id'
        );

        // add foreign key for table `{{%activity}}`
        $this->addForeignKey(
            '{{%fk-calendar-activity_id}}',
            '{{%calendar}}',
            'activity_id',
            '{{%activity}}',
            'id',
            'CASCADE'
        );

        // creates index for column `date_id`
        $this->createIndex(
            '{{%idx-calendar-date_id}}',
            '{{%calendar}}',
            'date_id'
        );

        // add foreign key for table `{{%dates}}`
        $this->addForeignKey(
            '{{%fk-calendar-date_id}}',
            '{{%calendar}}',
            'date_id',
            '{{%date}}',
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
            '{{%fk-calendar-activity_id}}',
            '{{%calendar}}'
        );

        // drops index for column `activity_id`
        $this->dropIndex(
            '{{%idx-calendar-activity_id}}',
            '{{%calendar}}'
        );

        // drops foreign key for table `{{%dates}}`
        $this->dropForeignKey(
            '{{%fk-calendar-date_id}}',
            '{{%calendar}}'
        );

        // drops index for column `date_id`
        $this->dropIndex(
            '{{%idx-calendar-date_id}}',
            '{{%calendar}}'
        );

        // drops foreign key for table `{{%times}}`
        $this->dropForeignKey(
            '{{%fk-calendar-time_id}}',
            '{{%calendar}}'
        );

        // drops index for column `time_id`
        $this->dropIndex(
            '{{%idx-calendar-time_id}}',
            '{{%calendar}}'
        );

        $this->dropTable('{{%calendar}}');
    }
}
