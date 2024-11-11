<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%localsellpoint_users}}`.
 */
class m241110_212443_create_localsellpoint_users_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Create the localsellpoint_userextra table
        $this->createTable('{{%localsellpoint_userextra}}', [
            'id' => $this->primaryKey(),
            'localsellpoint_id' => $this->integer()->notNull(),
            'userextra_id' => $this->integer()->notNull(),
            'role' => "ENUM('manager', 'salesperson') NOT NULL",
        ]);

        // Add foreign key for table `localsellpoint`
        $this->addForeignKey(
            'fk-localsellpoint_userextra-localsellpoint_id',
            '{{%localsellpoint_userextra}}',
            'localsellpoint_id',
            '{{%localsellpoint}}',
            'id',
            'CASCADE'
        );

        // Add foreign key for table `userextras`
        $this->addForeignKey(
            'fk-localsellpoint_userextra-userextra_id',
            '{{%localsellpoint_userextra}}',
            'userextra_id',
            '{{%userextras}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Drop foreign keys first
        $this->dropForeignKey(
            'fk-localsellpoint_userextra-localsellpoint_id',
            '{{%localsellpoint_userextra}}'
        );
        $this->dropForeignKey(
            'fk-localsellpoint_userextra-userextra_id',
            '{{%localsellpoint_userextra}}'
        );

        // Drop the localsellpoint_userextra table
        $this->dropTable('{{%localsellpoint_userextra}}');
    }
}
