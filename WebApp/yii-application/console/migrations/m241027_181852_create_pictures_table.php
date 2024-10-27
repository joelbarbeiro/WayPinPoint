<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%pictures}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%activities}}`
 * - `{{%user}}`
 */
class m241027_181852_create_pictures_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%pictures}}', [
            'id' => $this->primaryKey(),
            'activities_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'reviews' => $this->string(500)->notNull(),
            'uri' => $this->string(250)->notNull(),
        ]);

        // creates index for column `activities_id`
        $this->createIndex(
            '{{%idx-pictures-activities_id}}',
            '{{%pictures}}',
            'activities_id'
        );

        // add foreign key for table `{{%activities}}`
        $this->addForeignKey(
            '{{%fk-pictures-activities_id}}',
            '{{%pictures}}',
            'activities_id',
            '{{%activities}}',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-pictures-user_id}}',
            '{{%pictures}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-pictures-user_id}}',
            '{{%pictures}}',
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
            '{{%fk-pictures-activities_id}}',
            '{{%pictures}}'
        );

        // drops index for column `activities_id`
        $this->dropIndex(
            '{{%idx-pictures-activities_id}}',
            '{{%pictures}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-pictures-user_id}}',
            '{{%pictures}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-pictures-user_id}}',
            '{{%pictures}}'
        );

        $this->dropTable('{{%pictures}}');
    }
}
