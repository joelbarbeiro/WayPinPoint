<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%times}}`.
 */
class m241027_181849_create_time_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%time}}', [
            'id' => $this->primaryKey(),
            'hour' => $this->time()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%time}}');
    }
}
