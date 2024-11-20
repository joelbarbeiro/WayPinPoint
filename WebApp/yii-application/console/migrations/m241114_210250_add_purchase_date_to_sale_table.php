<?php

use yii\db\Migration;

/**
 * Class m241114_210250_add_purchase_date_to_sale_table
 */
class m241114_210250_add_purchase_date_to_sale_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('sale', 'purchase_date', $this->timestamp()->notNull()->after('total'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241114_210250_add_purchase_date_to_sale_table cannot be reverted.\n";

        return false;
    }
}
