<?php

use yii\db\Migration;

/**
 * Class m241128_201209_create_table_categories
 */
class m241128_201209_create_table_categories extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'description' => $this->string(400)->notNull(),
        ]);
        $this->batchInsert('category', ['id','description'], [
            [1,'Hiking'],
            [2,'Camping'],
            [3,'Cycling Outing'],
            [4,'Bird watching'],
            [5,'Sports'],
            [6,'Group Class'],
            [7,'Rock climbing'],
            [8,'Outings'],
            [9,'Live concerts'],
            [10,'Road trips'],
            [11,'Cultural tourism'],
            [12,'Other']
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%category}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241128_201209_create_table_categories cannot be reverted.\n";

        return false;
    }
    */
}
