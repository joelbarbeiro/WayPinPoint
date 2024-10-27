<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%invoices}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%sales}}`
 */
class m241027_181853_create_invoices_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%invoices}}', [
            'id' => $this->primaryKey(),
            'user' => $this->integer()->notNull(),
            'sales_id' => $this->integer()->notNull(),
        ]);

        // creates index for column `user`
        $this->createIndex(
            '{{%idx-invoices-user}}',
            '{{%invoices}}',
            'user'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-invoices-user}}',
            '{{%invoices}}',
            'user',
            '{{%user}}',
            'id',
            'CASCADE'
        );

        // creates index for column `sales_id`
        $this->createIndex(
            '{{%idx-invoices-sales_id}}',
            '{{%invoices}}',
            'sales_id'
        );

        // add foreign key for table `{{%sales}}`
        $this->addForeignKey(
            '{{%fk-invoices-sales_id}}',
            '{{%invoices}}',
            'sales_id',
            '{{%sales}}',
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
            '{{%fk-invoices-user}}',
            '{{%invoices}}'
        );

        // drops index for column `user`
        $this->dropIndex(
            '{{%idx-invoices-user}}',
            '{{%invoices}}'
        );

        // drops foreign key for table `{{%sales}}`
        $this->dropForeignKey(
            '{{%fk-invoices-sales_id}}',
            '{{%invoices}}'
        );

        // drops index for column `sales_id`
        $this->dropIndex(
            '{{%idx-invoices-sales_id}}',
            '{{%invoices}}'
        );

        $this->dropTable('{{%invoices}}');
    }
}
