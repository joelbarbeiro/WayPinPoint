<?php

use yii\db\Migration;

/**
 * Class m241105_185209_add_foreign_keys_to_cart_items_table
 */
class m241105_185209_add_foreign_keys_to_cart_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->renameTable('{{%cart_items}}', '{{%cart}}');

        $this->dropIndex('{{%idx-cart_items-user_id}}', '{{%cart}}');
        $this->dropIndex('{{%idx-cart_items-product_id}}', '{{%cart}}');

        $this->dropPrimaryKey('{{%user_id}}', '{{%cart}}');

        $this->alterColumn('{{%cart}}', 'user_id', $this->integer()->notNull());
        $this->alterColumn('{{%cart}}', 'product_id', $this->integer()->notNull());
        $this->alterColumn('{{%cart}}', 'quantity', $this->integer()->notNull());

        $this->addColumn('{{%cart}}', 'id', $this->primaryKey()->notNull());



        $this->addForeignKey(
            '{{%fk-cart-user_id}}',
            '{{%cart}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            '{{%fk-cart-product_id}}',
            '{{%cart}}',
            'product_id',
            '{{%activity}}',
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
        // Drop foreign keys
        $this->dropForeignKey('fk-cart-user_id', 'cart');
        $this->dropForeignKey('fk-cart-product_id', 'cart');
        $this->dropTable('{{%cart}}');

    }

    /**
     * Drop foreign key if it exists
     */
}
