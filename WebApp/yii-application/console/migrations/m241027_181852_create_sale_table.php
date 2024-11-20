<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%sale}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%activity}}`
 * - `{{%user}}`
 */
class m241027_181852_create_sale_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%sale}}', [
            'id' => $this->primaryKey(),
            'activity_id' => $this->integer()->notNull(),
            'buyer' => $this->integer()->notNull(),
            'total' => $this->float()->notNull(),
        ]);

        // creates index for column `activity_id`
        $this->createIndex(
            '{{%idx-sale-activity_id}}',
            '{{%sale}}',
            'activity_id'
        );

        // add foreign key for table `{{%activity}}`
        $this->addForeignKey(
            '{{%fk-sale-activity_id}}',
            '{{%sale}}',
            'activity_id',
            '{{%activity}}',
            'id',
            'CASCADE'
        );

        // creates index for column `buyer`
        $this->createIndex(
            '{{%idx-sale-buyer}}',
            '{{%sale}}',
            'buyer'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-sale-buyer}}',
            '{{%sale}}',
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
        // drops foreign key for table `{{%activity}}`
        $this->dropForeignKey(
            '{{%fk-sale-activity_id}}',
            '{{%sale}}'
        );

        // drops index for column `activity_id`
        $this->dropIndex(
            '{{%idx-sale-activity_id}}',
            '{{%sale}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-sale-buyer}}',
            '{{%sale}}'
        );

        // drops index for column `buyer`
        $this->dropIndex(
            '{{%idx-sale-buyer}}',
            '{{%sale}}'
        );

        $this->dropTable('{{%sale}}');
    }
}
