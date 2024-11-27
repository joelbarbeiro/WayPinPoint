<?php

use yii\db\Migration;

/**
 * Class m241125_204150_add_seller_to_sale
 */
class m241125_204150_add_seller_to_sale extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%sale}}', 'seller_id', $this->integer()->defaultValue(0));
        $this->alterColumn('{{%sale}}', 'seller_id', $this->integer()->notNull());
        $this->addForeignKey(
            '{{%fk-sale-seller_id}}',
            '{{%sale}}',
            'seller_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241125_204150_add_seller_to_sale cannot be reverted.\n";
        $this->dropForeignKey('{{%fk-sale-seller_id}}', '{{%sale}}');
        $this->dropColumn('{{%sale}}', 'seller_id');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241125_204150_add_seller_to_sale cannot be reverted.\n";

        return false;
    }
    */
}
