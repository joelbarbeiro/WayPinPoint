<?php

use yii\db\Migration;

/**
 * Class m241118_230639_add_primary_key_to_cart
 */
class m241118_230639_add_primary_key_to_cart extends Migration
{
    public function safeUp()
    {
        // Step 1: Drop existing foreign keys if they exist
        $this->dropForeignKeyIfExists('fk-cart-product_id', 'cart');
        $this->dropForeignKeyIfExists('fk-cart-user_id', 'cart');

        // Step 2: Add a new column `id` as the primary key
        $this->addColumn('cart', 'id', $this->primaryKey());
        $this->dropIndexIfExists('unique_cart_user_product', 'cart');
        // Step 3: Drop the composite primary key
        $this->dropPrimaryKey('PRIMARY', 'cart');

        // Step 4: Add foreign key for `product_id`
        $this->addForeignKey(
            'fk-cart-product_id', // FK name
            'cart',              // Current table
            'product_id',        // FK column
            'activities',        // Referenced table
            'id',                // Referenced column
            'CASCADE',           // On delete
            'CASCADE'            // On update
        );

        // Step 5: Add foreign key for `user_id`
        $this->addForeignKey(
            'fk-cart-user_id',   // FK name
            'cart',              // Current table
            'user_id',           // FK column
            'user',              // Referenced table
            'id',                // Referenced column
            'CASCADE',           // On delete
            'CASCADE'            // On update
        );
    }

    public function safeDown()
    {
        // Step 1: Drop the foreign keys
        $this->dropForeignKey('fk-cart-product_id', 'cart');
        $this->dropForeignKey('fk-cart-user_id', 'cart');

        // Step 2: Remove the `id` column
        $this->dropColumn('cart', 'id');

        // Step 3: Reinstate the composite primary key
        $this->addPrimaryKey('PRIMARY', 'cart', ['product_id', 'user_id']);
    }

    /**
     * Drops a foreign key if it exists.
     */
    private function dropForeignKeyIfExists($name, $table)
    {
        if ($this->db->schema->getTableSchema($table)->foreignKeys[$name] ?? false) {
            $this->dropForeignKey($name, $table);
        }
    }

    private function dropIndexIfExists($name, $table)
    {
        if (in_array($name, $this->db->schema->getTableSchema($table)->indexes ?? [])) {
            $this->dropIndex($name, $table);
        }
    }
}