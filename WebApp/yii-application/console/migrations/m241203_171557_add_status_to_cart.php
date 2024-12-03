<?php

use yii\db\Migration;

/**
 * Class m241203_171557_add_status_to_cart
 */
class m241203_171557_add_status_to_cart extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%cart}}', 'status', $this->integer()->notNull()->defaultValue(0)->comment('Status of the cart: 0 = Pending, 1 = Checked Out, etc.'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%cart}}', 'status');
        echo "m241203_171557_add_status_to_cart cannot be reverted.\n";
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241203_171557_add_status_to_cart cannot be reverted.\n";

        return false;
    }
    */
}
