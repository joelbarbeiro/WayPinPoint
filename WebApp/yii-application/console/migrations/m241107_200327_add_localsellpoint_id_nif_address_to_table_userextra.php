<?php

use yii\db\Migration;

/**
 * Class m241107_200327_add_localsellpoint_id_nif_address_to_table_userextra
 */
class m241107_200327_add_localsellpoint_id_nif_address_to_table_userextra extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameColumn('{{%userextra}}', 'user', 'user_id');
        $this->addColumn('{{%userextra}}', 'localsellpoint_id', $this->integer()->null()->after('user_id'));
        $this->addForeignKey(
            'fk-userextra-localsellpoint_id',
            '{{%userextra}}',
            'localsellpoint_id',
            '{{%localsellpoint}}',
            'id',
            'CASCADE'
        );
        $this->addColumn('{{%userextra}}', 'nif', $this->integer()->notNull()->unique()->after('localsellpoint_id'));
        $this->addColumn('{{%userextra}}', 'address', $this->string()->notNull()->after('nif'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->renameColumn('{{%userextra}}', 'user_id', 'user');
        $this->dropForeignKey(
            'fk-userextra-localsellpoint_id',
            '{{%userextra}}'
        );
        $this->dropColumn('{{%userextra}}', 'localsellpoint_id');
        $this->dropColumn('{{%userextra}}', 'nif');
        $this->dropColumn('{{%userextra}}', 'address');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241107_200327_add_localsellpoint_id_nif_address_to_table_userextra cannot be reverted.\n";

        return false;
    }
    */
}
