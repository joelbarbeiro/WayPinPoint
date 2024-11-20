<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%userextra}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m241103_150521_create_userextra_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%userextra}}', [
            'id' => $this->primaryKey(),
            'user' => $this->integer()->notNull(),
            'phone' => $this->string(20)->notnull(),
            'supplier' => $this->integer()->null()->defaultValue(0),
        ]);

        // creates index for column `user`
        $this->createIndex(
            '{{%idx-userextra-user}}',
            '{{%userextra}}',
            'user'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-userextra-user}}',
            '{{%userextra}}',
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
            '{{%fk-userextra-user}}',
            '{{%userextra}}'
        );

        // drops index for column `user`
        $this->dropIndex(
            '{{%idx-userextra-user}}',
            '{{%userextra}}'
        );

        $this->dropTable('{{%userextra}}');
    }
}
