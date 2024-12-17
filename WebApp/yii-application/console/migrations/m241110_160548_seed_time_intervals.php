<?php

use yii\db\Migration;

/**
 * Class m241110_160548_seed_time_intervals
 */
class m241110_160548_seed_time_intervals extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $timeIntervals = [];

        for ($hour = 0; $hour < 24; $hour++) {
            for ($minute = 0; $minute < 60; $minute += 15) {
                $timeIntervals[] = [sprintf('%02d:%02d', $hour, $minute)];
            }
        }

        $this->batchInsert('time', ['hour'], $timeIntervals);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241110_160548_seed_time_intervals cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241110_160548_seed_time_intervals cannot be reverted.\n";

        return false;
    }
    */
}
