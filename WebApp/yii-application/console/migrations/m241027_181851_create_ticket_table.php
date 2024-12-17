<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%ticket}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%activity}}`
 * - `{{%user}}`
 */
class m241027_181851_create_ticket_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%ticket}}', [
            'id' => $this->primaryKey(),
            'activity_id' => $this->integer()->notNull(),
            'participant' => $this->integer()->notNull(),
            'qr' => $this->string(250)->notNull(),
            'status' => $this->integer()->defaultValue(0)->notNull(),
        ]);

        // creates index for column `activity_id`
        $this->createIndex(
            '{{%idx-ticket-activity_id}}',
            '{{%ticket}}',
            'activity_id'
        );

        // add foreign key for table `{{%activity}}`
        $this->addForeignKey(
            '{{%fk-ticket-activity_id}}',
            '{{%ticket}}',
            'activity_id',
            '{{%activity}}',
            'id',
            'CASCADE'
        );

        // creates index for column `participant`
        $this->createIndex(
            '{{%idx-ticket-participant}}',
            '{{%ticket}}',
            'participant'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-ticket-participant}}',
            '{{%ticket}}',
            'participant',
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
            '{{%fk-ticket-activity_id}}',
            '{{%ticket}}'
        );

        // drops index for column `activity_id`
        $this->dropIndex(
            '{{%idx-ticket-activity_id}}',
            '{{%ticket}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-ticket-participant}}',
            '{{%ticket}}'
        );

        // drops index for column `participant`
        $this->dropIndex(
            '{{%idx-ticket-participant}}',
            '{{%ticket}}'
        );

        $this->dropTable('{{%ticket}}');
    }
}
