<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%localsellpoint}}`.
 */
class m241105_192257_add_manager_id_column_to_localsellpoint_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%localsellpoint}}', 'manager_id', $this->integer()->notNull()->after('user_id'));
        $this->addForeignKey(
            'fk-localsellpoint-manager_id',
            '{{%localsellpoint}}',
            'manager_id',
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
        $this->dropForeignKey('fk-localsellpoint-manager_id', '{{%localsellpoint}}');
        $this->dropColumn('{{%localsellpoint}}', 'manager_id');
    }
}