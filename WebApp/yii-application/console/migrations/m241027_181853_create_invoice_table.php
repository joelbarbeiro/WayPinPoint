<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%invoice}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%sale}}`
 */
class m241027_181853_create_invoice_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%invoice}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'sale_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `user`
        $this->createIndex(
            '{{%idx-invoice-user_id}}',
            '{{%invoice}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-invoice-user_id}}',
            '{{%invoice}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `sale_id`
        $this->createIndex(
            '{{%idx-invoice-sale_id}}',
            '{{%invoice}}',
            'sale_id'
        );

        // add foreign key for table `{{%sale}}`
        $this->addForeignKey(
            '{{%fk-invoice-sale_id}}',
            '{{%invoice}}',
            'sale_id',
            '{{%sale}}',
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
            '{{%fk-invoice-user_id}}',
            '{{%invoice}}'
        );

        // drops index for column `user`
        $this->dropIndex(
            '{{%idx-invoice-user_id}}',
            '{{%invoice}}'
        );

        // drops foreign key for table `{{%sale}}`
        $this->dropForeignKey(
            '{{%fk-invoice-sale_id}}',
            '{{%invoice}}'
        );

        // drops index for column `sale_id`
        $this->dropIndex(
            '{{%idx-invoice-sale_id}}',
            '{{%invoice}}'
        );

        $this->dropTable('{{%invoice}}');
    }
}
