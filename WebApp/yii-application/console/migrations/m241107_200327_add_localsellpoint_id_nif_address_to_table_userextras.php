<?php

use yii\db\Migration;

/**
 * Class m241107_200327_add_localsellpoint_id_nif_address_to_table_userextras
 */
class m241107_200327_add_localsellpoint_id_nif_address_to_table_userextras extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{%userextras}}', 'user', 'user_id');
        $this->addColumn('{{%userextras}}', 'localsellpoint_id', $this->integer()->notNull()->after('user_id'));
        $this->addForeignKey(
            'fk-userextras-localsellpoint_id',
            '{{%userextras}}',
            'localsellpoint_id',
            '{{%localsellpoint}}',
            'id',
            'CASCADE'
        );
        $this->addColumn('{{%userextras}}', 'nif', $this->integer()->notNull()->unique()->after('localsellpoint_id'));
        $this->addColumn('{{%userextras}}', 'address', $this->string()->notNull()->after('nif'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('{{%userextras}}', 'user_id', 'user');
        $this->dropForeignKey(
            'fk-userextras-localsellpoint_id',
            '{{%userextras}}'
        );
        $this->dropColumn('{{%userextras}}', 'localsellpoint_id');
        $this->dropColumn('{{%userextras}}', 'nif');
        $this->dropColumn('{{%userextras}}', 'address');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241107_200327_add_localsellpoint_id_nif_address_to_table_userextras cannot be reverted.\n";

        return false;
    }
    */
}
