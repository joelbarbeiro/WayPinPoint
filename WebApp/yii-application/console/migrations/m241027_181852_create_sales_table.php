<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sales}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%activities}}`
 * - `{{%user}}`
 */
class m241027_181852_create_sales_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sales}}', [
            'id' => $this->primaryKey(),
            'activities_id' => $this->integer()->notNull(),
            'buyer' => $this->integer()->notNull(),
            'total' => $this->float()->notNull(),
        ]);

        // creates index for column `activities_id`
        $this->createIndex(
            '{{%idx-sales-activities_id}}',
            '{{%sales}}',
            'activities_id'
        );

        // add foreign key for table `{{%activities}}`
        $this->addForeignKey(
            '{{%fk-sales-activities_id}}',
            '{{%sales}}',
            'activities_id',
            '{{%activities}}',
            'id',
            'CASCADE'
        );

        // creates index for column `buyer`
        $this->createIndex(
            '{{%idx-sales-buyer}}',
            '{{%sales}}',
            'buyer'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-sales-buyer}}',
            '{{%sales}}',
            'buyer',
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
            '{{%fk-sales-activities_id}}',
            '{{%sales}}'
        );

        // drops index for column `activities_id`
        $this->dropIndex(
            '{{%idx-sales-activities_id}}',
            '{{%sales}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-sales-buyer}}',
            '{{%sales}}'
        );

        // drops index for column `buyer`
        $this->dropIndex(
            '{{%idx-sales-buyer}}',
            '{{%sales}}'
        );

        $this->dropTable('{{%sales}}');
    }
}
