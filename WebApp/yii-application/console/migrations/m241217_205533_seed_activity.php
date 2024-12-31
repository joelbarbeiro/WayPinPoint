<?php

use yii\db\Migration;

/**
 * Class m241217_205533_seed_activity
 */
class m241217_205533_seed_activity extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('activity',
            ['name', 'description', 'photo', 'maxpax', 'priceperpax', 'address', 'category_id', 'user_id', 'status'],
            [
                ['Yoga Class', 'Relaxing yoga session for beginners.', 'BDXY3Ih8XhADZTKM.jpg', 15, 10.50, '123 Wellness St, City', 1, 2, 1],
                ['Cooking Workshop', 'Learn to cook Italian cuisine.', 'CsyUFl49b7WISizS.jpg', 10, 30.00, '456 Kitchen Ave, City', 2, 3, 1],
                ['Photography Walk', 'Capture nature and landscapes.', 'hd2UBNjh0xf-_tis.jpg', 20, 15.00, '789 Park Rd, City', 3, 3, 1],
                ['Rock Climbing', 'Outdoor rock climbing for all levels.', 'jILk_o8lkagycDXR.jpg', 8, 50.00, 'Mountain Base, Highlands', 4, 2, 1],
                ['Painting Class', 'Learn watercolor techniques.', 'NtgkN4GX3xTl_erN.jpg', 12, 20.00, '321 Art Lane, City', 5, 3, 1],
                ['Swimming Lesson', 'Beginner swimming lessons.', 'RKkYk5i4bAuyKg4Y.jpg', 10, 25.00, 'City Pool Center, Main St', 6, 2, 1],
                ['Cycling Tour', 'Explore the city on two wheels.', 'sKU4K-SB6L2emHDf.jpg', 15, 12.50, 'Central Plaza, City', 7, 3, 1],
                ['Music Workshop', 'Learn to play the guitar.', 'SryE67L-I3mz1U0h.jpg', 6, 40.00, 'Music Academy, 12th St', 8, 3, 1],
                ['Fitness Bootcamp', 'Intense outdoor workout session.', 'YSK1iO4rCQiY8PWz.jpg', 20, 35.00, 'Hilltop Park, City', 9, 2, 1],
                ['Language Exchange', 'Practice Spanish with native speakers.', 'BrmfyPNe61RZqhJS.jpg', 25, 5.00, 'Library Hall, Downtown', 10, 2, 1],
            ]
        );

        $this->batchInsert('date', ['id', 'date'], [
            [1, '2025-06-10'],
            [2, '2025-06-15'],
            [3, '2025-06-20'],
            [4, '2025-06-25'],
            [5, '2025-07-01'],
            [6, '2025-07-05'],
            [7, '2025-07-10'],
            [8, '2025-07-15'],
            [9, '2025-08-01'],
            [10, '2025-08-10'],
        ]);
        $time_id = 30;
        $calendarData = [];

        for ($i = 1; $i <= 10; $i++) {
            $calendarData[] = [$i, $i, $time_id];
            $time_id++;
        }

        $this->batchInsert('calendar', ['activity_id', 'date_id', 'time_id'], $calendarData);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m241217_205533_seed_activity cannot be reverted.\n";

        $this->delete('calendar', ['activity_id' => range(1, 10)]);

        $this->delete('date', ['id' => range(1, 10)]);

        $this->delete('activities', ['id' => range(1, 10)]);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m241217_205533_seed_activity cannot be reverted.\n";

        return false;
    }
    */
}
