<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%picture}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%activity}}`
 * - `{{%user}}`
 */
class m241027_181852_create_picture_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%picture}}', [
            'id' => $this->primaryKey(),
            'activity_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'reviews' => $this->string(500)->notNull(),
            'uri' => $this->string(250)->notNull(),
        ]);

        // creates index for column `activity_id`
        $this->createIndex(
            '{{%idx-picture-activity_id}}',
            '{{%picture}}',
            'activity_id'
        );

        // add foreign key for table `{{%activity}}`
        $this->addForeignKey(
            '{{%fk-picture-activity_id}}',
            '{{%picture}}',
            'activity_id',
            '{{%activity}}',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-picture-user_id}}',
            '{{%picture}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-picture-user_id}}',
            '{{%picture}}',
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
            '{{%fk-picture-activity_id}}',
            '{{%picture}}'
        );

        // drops index for column `activity_id`
        $this->dropIndex(
            '{{%idx-picture-activity_id}}',
            '{{%picture}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-picture-user_id}}',
            '{{%picture}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-picture-user_id}}',
            '{{%picture}}'
        );

        $this->dropTable('{{%picture}}');
    }
}
