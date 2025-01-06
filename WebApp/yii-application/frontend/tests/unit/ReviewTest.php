<?php

namespace frontend\tests;

use common\models\Activity;
use common\models\Category;
use common\models\User;
use frontend\models\Review;

class ReviewTest extends \Codeception\Test\Unit
{
    /**
     * @var \frontend\tests\UnitTester
     */

    protected $tester;
    protected $user;
    protected $activity;
    protected $category;

    private const VALID_REVIEW_DESCRIPTION = 'Standard message for review';
    private const VALID_NAME = 'Test activity';
    private const VALID_DESCRIPTION = 'Test activity description';
    private const VALID_INT = '10';
    private const VALID_ADDRESS = 'Test activity address nÂº 4 3 esq, 1500-000, Lisboa';
    private const VALID_PHOTO = 'c:\testFile.jpg';
    private const VALID_DATE = ['0' => '2025-01-01', '1' => '2025-02-01'];
    private const VALID_HOUR = ['0' => '10', '1' => '20'];

    protected function _before()
    {
        $this->user = $this->createUser();
        $this->user->save();

        $this->category = $this->createCategory();
        $this->category->save();

        $this->activity = $this->createActivity();
        $this->activity->save();

        $review = $this->createReview();
        $review->save();
    }

    protected function _after()
    {
    }

    public function createReview()
    {
        $review = new Review();
        $review->score = 4;
        $review->message = self::VALID_REVIEW_DESCRIPTION;
        $review->activity_id = $this->activity->id;
        $review->user_id = $this->user->id;
        $review->created_at = time();

        return $review;
    }


    public function testValidCreation()
    {
        $review = $this->createReview();

        $this->assertTrue($review->validate('id'));
        $this->assertTrue($review->validate('score'));
        $this->assertTrue($review->validate('message'));
        $this->assertTrue($review->validate('activity_id'));
        $this->assertTrue($review->validate('created_at'));

    }

    public function testCreateInvalidNullReview()
    {
        // Test to see if the not null values assertFalse when passing null
        $review = new Review();
        $review->score = null;
        $review->message = "Like it";
        $review->activity_id = null;
        $review->user_id = null;
        $review->created_at = time();

        $this->assertFalse($review->validate('score'));
        $this->assertFalse($review->validate('user_id'));
        $this->assertFalse($review->validate('activity_id'));
    }

    public function testCreateInvalidScoreReview()
    {
        //Test the score to be over 5 ( it can only be between 0-5 )
        $review = Review::findOne(['message' => 'Standard message for review']);

        $review->score = 10;
        $this->assertFalse($review->validate('score'));

        $review->score = -1;
        $this->assertFalse($review->validate('score'));

        $review->score = 5;
        $this->assertTrue($review->validate('score'));
    }

    public function testSaveAndRead()
    {
        $review = $this->createReview();
        $review->score = 4;
        $review->message = "I love this activity";
        $review->save();

        $reviewFromDatabase = Review::findOne(['message' => 'I love this activity']);
        $this->assertNotNull($reviewFromDatabase);
        $this->assertEquals(4, $reviewFromDatabase->score);
    }

    public function testUpdateAndRead(){

        $reviewFromDatabase = Review::findOne(['message' => 'Standard message for review']);
        $reviewFromDatabase->message = 'Altered message';
        $reviewFromDatabase->save();

        $reviewAlteredFromDatabase = Review::findOne($reviewFromDatabase->id);
        $this->assertEquals('Altered message', $reviewAlteredFromDatabase->message);
    }
    public function testDeleteAndRead(){
        $reviewToDelete = Review::findOne(['message' => 'Standard message for review']);
        $result =  $reviewToDelete->delete();
        $this->assertEquals(1,$result);

        $reviewFromDatabase = Review::findOne(['message' => 'Standard message for review']);
        $this->assertNull($reviewFromDatabase);
    }

    public function createUser()
    {
        $user = new User();
        $user->username = "user test";
        $user->email = "usertest@gmail.com";
        $user->password = "usertest123123";

        return $user;
    }

    public function createCategory(){
        $category = new Category();
        $category->description = "Test category";

        return $category;
    }

    public function createActivity()
    {

        $activity = new Activity();
        $activity->name = self::VALID_NAME;
        $activity->description = self::VALID_DESCRIPTION;
        $activity->photo = self::VALID_PHOTO;
        $activity->photoFile = self::VALID_PHOTO;
        $activity->maxpax = self::VALID_INT;
        $activity->priceperpax = self::VALID_INT;
        $activity->address = self::VALID_ADDRESS;
        $activity->category_id = $this->category->id;
        $activity->date = self::VALID_DATE;
        $activity->hour = self::VALID_HOUR;
        $activity->user_id = $this->user->id;

        return $activity;
    }
}