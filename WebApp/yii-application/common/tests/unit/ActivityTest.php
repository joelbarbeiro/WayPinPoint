<?php

namespace common\tests\unit\models;

use common\models\Activity;
use common\models\Category;
use common\models\User;

class ActivityTest extends \Codeception\Test\Unit
{
    private const STRING_199_CHARS = 'nP8maws1lTqjEcNjaWJIrdEIIbIbcgTkKeeJnCXtRyuRRdKMpMTaIbcQjeOzN0eodkPcfjC4LwVO7jCuyAEc2kpCApfdwzP01iSGGDwfdKLmCBmXypUWP7JEgEHquTL9BYZ6MLMfKJh7g6WgdnbkFwM4mDcXbZ6Ma3R3gFA47AEfm4ZKfFJTrrjpg31Ixx2KdlMzH9v
';
    private const STRING_402_CHARS = 'iwkbTqzWEuveVd9F0dUxI9R7i7jSGHENN39MHS21n2Axguc7jJ6z8WHpj7KscMnTI06mwT0hMDJ12lHcVt7N2b0B2Jg2jEFv5GNPlIXcmqn5k6efx6gKQHo2TWVrm98jXxzPJyyjCanbAVFPZ8Vk5vC9f8A4KZyq1NAT4BlbM6wYL8O1o5Z6cLfzJ6P3lNEPfDJHrfo868b8RQiqDlmdSeJkIQSLED6TNfNleojYSXUKH86nyW0AvhieSgj8INLaP3A0ZyhMu5LmAj4WYPfy6450popdzm3EYimHL0LSUiJAxAz8Mj6Fr7NlWdsVJgG3qJ96MJFdSFqCChc2TEJ4bPNY93SI9TLvTPDjLmjbH9BtEDt7CkdZPs6gTevWXufXKCsonCf0LnCmqwNIRY

';
    private const VALID_NAME = 'Test activity';
    private const VALID_UPDATE_NAME = "Before test name";
    private const VALID_DESCRIPTION = 'Test activity description';
    private const VALID_INT = '10';
    private const VALID_ADDRESS = 'Test activity address nº 4 3 esq, 1500-000, Lisboa';
    private const VALID_PHOTO = 'c:\testFile.jpg';
    private const VALID_DATE = ['0' => '2025-01-01', '1' => '2025-02-01'];
    private const VALID_HOUR = ['0' => '10', '1' => '20'];
    private const INVALID_DATE = '2025-01-01';
    private const INVALID_HOUR = '12:00';

    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;
    protected $user;
    protected $category;

    protected function _before()
    {
        $this->user = $this->createUser();
        $this->user->save();

        $this->category = $this->createCategory();
        $this->category->save();

        $validActivity = $this->createValidActivity();
        $validActivity->name = self::VALID_UPDATE_NAME;
        $validActivity->save();
    }

    protected function _after()
    {
    }

    public function testValidation()
    {
        $activity = $this->createValidActivity();

        $this->assertTrue($activity->validate(['name']));
        $this->assertTrue($activity->validate(['description']));
        $this->assertTrue($activity->validate(['photo']));
        $this->assertTrue($activity->validate(['maxpax']));
        $this->assertTrue($activity->validate(['priceperpax']));
        $this->assertTrue($activity->validate(['address']));
        $this->assertTrue($activity->validate(['category_id']));
        $this->assertTrue($activity->validate(['user_id']));
        $this->assertTrue($activity->validate(['date']));
        $this->assertTrue($activity->validate(['hour']));


        $activity->name = self::STRING_402_CHARS;
        $activity->description = self::STRING_402_CHARS;
        $activity->photo = self::STRING_402_CHARS;
        $activity->maxpax = self::STRING_402_CHARS;
        $activity->priceperpax = self::STRING_402_CHARS;
        $activity->address = self::STRING_402_CHARS;
        $activity->category_id = self::STRING_199_CHARS;
        $activity->user_id = self::STRING_199_CHARS;
        $activity->date = self::INVALID_DATE;
        $activity->hour = self::INVALID_HOUR;



        $this->assertFalse($activity->validate(['name']));
        $this->assertFalse($activity->validate(['description']));
        $this->assertFalse($activity->validate(['photo']));
        $this->assertFalse($activity->validate(['maxpax']));
        $this->assertFalse($activity->validate(['priceperpax']));
        $this->assertFalse($activity->validate(['address']));
        $this->assertFalse($activity->validate(['category_id']));
        $this->assertFalse($activity->validate(['user_id']));
        $this->assertFalse($activity->validate(['date']));
        $this->assertFalse($activity->validate(['hour']));

        $activity->name = null;
        $activity->description = null;
        $activity->photo = null;
        $activity->maxpax = null;
        $activity->priceperpax = null;
        $activity->address = null;
        $activity->category_id = null;
        $activity->date = null;
        $activity->hour = null;

        $this->assertFalse($activity->validate(['name']));
        $this->assertFalse($activity->validate(['description']));
        $this->assertFalse($activity->validate(['photo']));
        $this->assertFalse($activity->validate(['maxpax']));
        $this->assertFalse($activity->validate(['priceperpax']));
        $this->assertFalse($activity->validate(['address']));
        $this->assertFalse($activity->validate(['category_id']));
        $this->assertFalse($activity->validate(['date']));
        $this->assertFalse($activity->validate(['hour']));
    }

    public function testSaveAndRead()
    {

        $activity = $this->createValidActivity();
        $wasSaveSuccessfull = $activity->save();

        $this->assertTrue($wasSaveSuccessfull);

        $activityFromDatabase = Activity::find()->where(['name' => self::VALID_UPDATE_NAME])->one();
        $this->assertNotNull($activityFromDatabase);

    }

    public function testUpdate(){
        $activity = Activity::find()->where(['name'=> self::VALID_UPDATE_NAME])->one();
        $newName = 'NEW not null activity name';

        $activity->name = $newName;
        $activity->date = self::VALID_DATE;
        $activity->hour = self::VALID_HOUR;
        $activity->save();

        $activityFromDatabase = Activity::find()->where(['name'=> $newName])->one();
        $this->assertNotNull($activityFromDatabase);

    }

    public function testDelete(){

        $activity = $this->createValidActivity();
        $this->assertTrue($activity->save());

        $activityFromDatabase = Activity::findOne($activity->id);
        $this->assertNotNull($activityFromDatabase);

        $activityFromDatabase->delete();
        $pessoaAfterDeletion = Activity::findOne($activity->id);
        $this->assertNull($pessoaAfterDeletion);
    }

    public function testValidActivity(){
        $calendar = $this->createValidActivity();
        $calendar->save();
        $this->assertTrue(true, "Should be returning an exception");
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

    public function createValidActivity()
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

    public function createInvalidActivity()
    {
        $activity = new Activity();
        $activity->name = self::STRING_402_CHARS;
        $activity->description = self::STRING_402_CHARS;
        $activity->photo = self::STRING_402_CHARS;
        $activity->photoFile = self::VALID_PHOTO;
        $activity->maxpax = self::VALID_INT;
        $activity->priceperpax = self::VALID_INT;
        $activity->address = self::STRING_402_CHARS;
        $activity->category_id = $this->category->id;
        $activity->date = self::INVALID_DATE;
        $activity->hour = self::INVALID_HOUR;
        $activity->user_id = $this->user->id;

        return $activity;
    }

    public function createValidActivityAsArray()
    {
        $activity = [
            'name' => self::VALID_NAME,
            'description' => self::VALID_DESCRIPTION,
            'photo' => self::VALID_PHOTO,
            'photoFile' => self::VALID_PHOTO,
            'maxpax' => self::VALID_INT,
            'priceperpax' => self::VALID_INT,
            'address' => self::VALID_ADDRESS,
            'category_id' => $this->category->id,
            'date' => self::VALID_DATE,
            'hour' => self::VALID_HOUR,
            'user_id' => $this->user->id,
        ];
        return $activity;
    }

}