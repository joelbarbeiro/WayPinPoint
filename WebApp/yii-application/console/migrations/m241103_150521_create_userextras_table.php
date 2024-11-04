<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%userextras}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m241103_150521_create_userextras_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%userextras}}', [
            'id' => $this->primaryKey(),
            'user' => $this->integer()->notNull(),
            'phone' => $this->string(20)->notnull(),
            'supplier' => $this->integer()->notnull()->defaultValue(0),
        ]);

        // creates index for column `user`
        $this->createIndex(
            '{{%idx-userextras-user}}',
            '{{%userextras}}',
            'user'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-userextras-user}}',
            '{{%userextras}}',
            'user',
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
            '{{%fk-userextras-user}}',
            '{{%userextras}}'
        );

        // drops index for column `user`
        $this->dropIndex(
            '{{%idx-userextras-user}}',
            '{{%userextras}}'
        );

        $this->dropTable('{{%userextras}}');
    }
}
