<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dates}}`.
 */
class m241027_181846_create_dates_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%dates}}', [
            'id' => $this->primaryKey(),
            'date' => $this->date()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%dates}}');
    }
}
