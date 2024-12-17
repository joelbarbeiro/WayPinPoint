<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%review}}`.
 */
class m241119_142306_create_review_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%review}}', [
            'id' => $this->primaryKey(),
            'activity_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'score' => $this->tinyInteger(1)->notNull()->check('score BETWEEN 1 AND 5'),
            'message' => $this->text()->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk-review-activity_id',
            '{{%review}}',
            'activity_id',
            '{{%activity}}',
            'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-review-user_id',
            '{{%review}}',
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
        $this->dropForeignKey('fk-review-activity_id', 'review');
        $this->dropTable('{{%review}}');
    }
}