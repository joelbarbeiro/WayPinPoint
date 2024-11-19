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
        // Drop foreign keys if they already exist
        $this->dropForeignKeyIfExists('fk-cart-user_id', 'cart_items');
        $this->dropForeignKeyIfExists('fk-cart-product_id', 'cart_items');

        // Ensure cart_items uses InnoDB engine if needed
        $this->execute('ALTER TABLE cart_items ENGINE=InnoDB');

        // Ensure activities uses InnoDB engine if needed
        $this->execute('ALTER TABLE activities ENGINE=InnoDB');

        // Add foreign key for User_id
        $this->addForeignKey(
            'fk-cart-user_id',
            'cart_items',
            'User_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // Add foreign key for Product_id
        $this->addForeignKey(
            'fk-cart-product_id',
            'cart_items',
            'Product_id',
            'activities',
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
        $this->dropForeignKey('fk-cart-user_id', 'cart_items');
        $this->dropForeignKey('fk-cart-product_id', 'cart_items');
    }

    /**
     * Drop foreign key if it exists
     */
    private function dropForeignKeyIfExists($name, $table)
    {
        $db = $this->db;
        if ($db->getSchema()->getTableSchema($table, true) && $db->getSchema()->getTableSchema($table, true)->foreignKeys) {
            $foreignKeys = $db->getSchema()->getTableSchema($table, true)->foreignKeys;
            foreach ($foreignKeys as $fkName => $foreignKey) {
                if ($fkName === $name) {
                    $this->dropForeignKey($name, $table);
                }
            }
        }
    }
}
