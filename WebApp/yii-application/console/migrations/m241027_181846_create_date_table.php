<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%dates}}`.
 */
class m241027_181846_create_date_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%date}}', [
            'id' => $this->primaryKey(),
            'date' => $this->date()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%date}}');
    }
}
