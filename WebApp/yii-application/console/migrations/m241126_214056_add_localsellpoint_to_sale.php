<?php

use yii\db\Migration;

/**
 * Class m241126_214056_add_localsellpoint_to_sale
 */
class m241126_214056_add_localsellpoint_to_sale extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%sale}}', 'localsellpoint_id', $this->integer());
        $this->alterColumn('{{%sale}}', 'localsellpoint_id', $this->integer()->notNull());
        $this->addForeignKey(
            '{{%fk-sale-localsellpoint_id}}',
            '{{%sale}}',
            'localsellpoint_id',
            '{{%userextra}}',
            'localsellpoint_id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241126_214056_add_localsellpoint_to_sale cannot be reverted.\n";
        $this->dropForeignKey('{{%fk-sale-localsellpoint_id}}', '{{%sale}}');
        $this->dropColumn('{{%sale}}', 'localsellpoint_id');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241126_214056_add_localsellpoint_to_sale cannot be reverted.\n";

        return false;
    }
    */
}
