<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%reviews}}`.
 */
class m241119_142306_create_reviews_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%reviews}}', [
            'id' => $this->primaryKey(),
            'activity_id' => $this->integer()->notNull(),
            'score' => $this->tinyInteger(1)->notNull()->check('score BETWEEN 1 AND 5'),
            'message' => $this->text()->null(),
            'created_at' => $this->timestamp()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk-reviews-activity_id',
            '{{%reviews}}',
            'activity_id',
            '{{%activities}}',
            'id',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-reviews-activity_id', 'reviews');
        $this->dropTable('{{%reviews}}');
    }
}