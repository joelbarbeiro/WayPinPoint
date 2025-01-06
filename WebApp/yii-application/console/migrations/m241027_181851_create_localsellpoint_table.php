<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%localsellpoint}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m241027_181851_create_localsellpoint_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%localsellpoint}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'address' => $this->string(400)->notNull(),
            'name' => $this->string(100)->notNull(),
            'status' => $this->integer()->notNull()->defaultValue('1')
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-localsellpoint-user_id}}',
            '{{%localsellpoint}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-localsellpoint-user_id}}',
            '{{%localsellpoint}}',
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
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-localsellpoint-user_id}}',
            '{{%localsellpoint}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-localsellpoint-user_id}}',
            '{{%localsellpoint}}'
        );

        $this->dropTable('{{%localsellpoint}}');
    }
}
