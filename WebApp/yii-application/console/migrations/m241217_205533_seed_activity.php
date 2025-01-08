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
              ['Mountain Adventure Hike', 'Explore scenic trails with breathtaking mountain views.', 'WLxQvbMyfas.jpg', 15, 50.00, '123 Trailhead Lane, Mountainview', 1, 2, 1],
              ['Forest Camping Escape', 'Experience a weekend under the stars surrounded by nature.', 'GxxHf3fJYPI.jpg', 20, 80.00, '456 Woodland Road, Nature Park', 2, 2, 1],
              ['Coastal Cycling Tour', 'Ride along picturesque coastal roads with ocean views.', 'LR5-OExz4eE.jpg', 10, 40.00, '789 Seaside Avenue, Coastal Town', 3, 3, 1],
              ['Wetland Birdwatching Tour', 'Observe unique bird species in their natural habitats.', 'ITjiVXcwVng.jpg', 8, 60.00, '321 Wetland Reserve, Birdland', 4, 3, 1],
              ['Weekend Soccer Tournament', 'Join a friendly soccer tournament in the local community.', 'WvDYdXDzkhs.jpg', 22, 20.00, '111 Sports Arena, Downtown', 5, 2, 1],
              ['Yoga in the Park', 'Relax and recharge with a morning yoga session in the park.', 'J6cg9TA8-e8.jpg', 25, 30.00, '234 Greenway Park, Downtown', 6, 3, 1],
              ['Forest Birdwatching Adventure', 'Discover exotic birds in dense forest habitats.', 'ed-XvG01qV-0.jpg', 6, 70.00, '567 Forest Trail, Birdland', 4, 2, 1],
              ['Rock Climbing for Beginners', 'Learn the basics of rock climbing with professional guides.', '7qTA0RDgXb0.jpg', 12, 90.00, '890 Cliffside Drive, Adventure City', 7, 3, 1],
              ['Picnic by the Lake', 'Enjoy a relaxing outing by the serene lake.', 'ZHVqVY1NZJQ.jpg', 30, 20.00, '678 Lakeside Avenue, Relaxation Town', 8, 3, 1],
              ['Summer Music Festival', 'Experience live music from top artists under the stars.', '8CqDvPuo_kI.jpg', 500, 120.00, '900 Festival Grounds, Music City', 9, 2, 1],
              ['Scenic Road Trip Adventure', 'Drive through breathtaking landscapes with a group of adventurers.', 'MBz7o-W8kUE.jpg', 4, 200.00, '123 Highway Route, Adventure Land', 10, 3, 1],
              ['City Heritage Walking Tour', 'Discover the rich history and culture of the city.', 'yBXF8NNlHcg.jpg', 20, 25.00, '123 Heritage Square, Oldtown', 11, 2, 1],
              ['Stargazing Night Out', 'Spend the night watching stars and learning about constellations.', 'V6s1cmE39XM.jpg', 10, 50.00, '456 Observatory Road, Galaxy Point', 12, 3, 1],
              ['Art and Wine Workshop', 'Unleash your creativity while enjoying fine wine.', '8T7NG5tvC8E.jpg', 15, 60.00, '789 Art Studio Lane, Creative Town', 12, 3, 1],
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
        $activitiesWithTwoDates = [1, 2, 3, 4, 5, 8, 10, 11, 12, 13];
        $dateCounter = 1;
        for ($activity_id = 1; $activity_id <= 14; $activity_id++) {
          $calendarData[] = [$activity_id, $dateCounter, $time_id];
          $time_id++;
          if (in_array($activity_id, $activitiesWithTwoDates)) {
            $dateCounter = $dateCounter % 10 + 1;
            $calendarData[] = [$activity_id, $dateCounter, $time_id];
            $time_id++;
          }
          $dateCounter = $dateCounter % 10 + 1;
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
