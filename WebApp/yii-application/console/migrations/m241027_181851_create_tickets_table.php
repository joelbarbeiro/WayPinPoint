<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%tickets}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%activities}}`
 * - `{{%user}}`
 */
class m241027_181851_create_tickets_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%tickets}}', [
            'id' => $this->primaryKey(),
            'activities_id' => $this->integer()->notNull(),
            'participant' => $this->integer()->notNull(),
            'qr' => $this->string(250)->notNull(),
            'status' => $this->integer()->defaultValue(0)->notNull(),
        ]);

        // creates index for column `activities_id`
        $this->createIndex(
            '{{%idx-tickets-activities_id}}',
            '{{%tickets}}',
            'activities_id'
        );

        // add foreign key for table `{{%activities}}`
        $this->addForeignKey(
            '{{%fk-tickets-activities_id}}',
            '{{%tickets}}',
            'activities_id',
            '{{%activities}}',
            'id',
            'CASCADE'
        );

        // creates index for column `participant`
        $this->createIndex(
            '{{%idx-tickets-participant}}',
            '{{%tickets}}',
            'participant'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-tickets-participant}}',
            '{{%tickets}}',
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
        // drops foreign key for table `{{%activities}}`
        $this->dropForeignKey(
            '{{%fk-tickets-activities_id}}',
            '{{%tickets}}'
        );

        // drops index for column `activities_id`
        $this->dropIndex(
            '{{%idx-tickets-activities_id}}',
            '{{%tickets}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-tickets-participant}}',
            '{{%tickets}}'
        );

        // drops index for column `participant`
        $this->dropIndex(
            '{{%idx-tickets-participant}}',
            '{{%tickets}}'
        );

        $this->dropTable('{{%tickets}}');
    }
}
