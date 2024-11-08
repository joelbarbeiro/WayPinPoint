<?php

use yii\db\Migration;

/**
 * Class m241107_204534_add_hour_id_to_table_dates
 */
class m241107_204534_add_hour_id_to_table_dates extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%dates}}', '{{%hour_id}}', $this->integer()->notNull()->after('id'));
        $this->addForeignKey(
            'fk-dates-hour_id',
            '{{%dates}}',
            'hour_id',
            '{{%times}}',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(
            'fk-dates-hour_id',
            '{{%dates}}');
        $this->dropColumn('{{%dates}}', 'hour_id');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241107_204534_add_hour_id_to_table_dates cannot be reverted.\n";

        return false;
    }
    */
}
