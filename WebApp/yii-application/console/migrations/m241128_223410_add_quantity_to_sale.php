<?php

use yii\db\Migration;

/**
 * Class m241128_223410_add_quantity_to_sale
 */
class m241128_223410_add_quantity_to_sale extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%sale}}', 'quantity', $this->integer());
        $this->alterColumn('{{%sale}}', 'quantity', $this->integer()->notNull());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241128_223410_add_quantity_to_sale cannot be reverted.\n";
        $this->dropColumn('{{%sale}}', 'quantity');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241128_223410_add_quantity_to_sale cannot be reverted.\n";

        return false;
    }
    */
}
