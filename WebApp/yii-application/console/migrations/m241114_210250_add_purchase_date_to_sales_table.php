<?php

use yii\db\Migration;

/**
 * Class m241114_210250_add_purchase_date_to_sales_table
 */
class m241114_210250_add_purchase_date_to_sales_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('sales', 'purchase_date', $this->timestamp()->notNull()->after('total'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241114_210250_add_purchase_date_to_sales_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241114_210250_add_purchase_date_to_sales_table cannot be reverted.\n";

        return false;
    }
    */
}
