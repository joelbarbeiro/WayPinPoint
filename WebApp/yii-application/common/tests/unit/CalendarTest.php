<?php
namespace common\tests\unit\models;

use common\models\Calendar;
use common\models\Category;
use common\models\Time;
use common\models\Date;
use common\models\Activity;
use common\models\User;


class CalendarTest extends \Codeception\Test\Unit
{
    private const VALID_NAME = 'Test activity';
    private const INVALID_ID = "test";
    private const VALID_UPDATE_NAME = "Before test name";
    private const VALID_DESCRIPTION = 'Test activity description';
    private const VALID_INT = '10';
    private const VALID_ADDRESS = 'Test activity address nº 4 3 esq, 1500-000, Lisboa';
    private const VALID_PHOTO = 'c:\testFile.jpg';
    private const VALID_DATE = "2025-01-01";
    private const VALID_HOUR = "10:00";

    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;
    protected $calendar;
    protected $time;
    protected $date;
    protected $activity;
    protected $user;
    protected $category;
    
    protected function _before()
    {
        $this->user = $this->createUser();
        $this->user->save();

        $this->category = $this->createCategory();
        $this->category->save();

        $this->time = $this->createTime();
        $this->time->hour = "11:00";
        $this->time->save();

        $this->date = $this->createDate();
        $this->date->date = "2025-01-05";
        $this->date->save();

        $this->activity = $this->createActivity();
        $this->activity->name = self::VALID_NAME;
        $this->activity->save();

        $this->calendar = $this->createCalendar();
        $this->calendar->save();

    }

    // tests
    public function testSomeFeature()
    {
        $this->date = $this->createDate();
        $this->date->save();

        $this->time = $this->createTime();
        $this->time->save();

        $this->activity = $this->createActivity();
        $this->activity->save();

        $calendar = $this->createCalendar();
        $this->assertTrue($calendar->validate(['activity_id']));
        $this->assertTrue($calendar->validate(['date_id']));
        $this->assertTrue($calendar->validate(['time_id']));
        $this->assertTrue($calendar->validate(['status']));

        $calendar->activity_id = self::INVALID_ID;
        $calendar->date_id = self::INVALID_ID;
        $calendar->time_id = self::INVALID_ID;
        $calendar->status = self::INVALID_ID;

        $this->assertFalse($calendar->validate(['activity_id']));
        $this->assertFalse($calendar->validate(['date_id']));
        $this->assertFalse($calendar->validate(['time_id']));
        $this->assertFalse($calendar->validate(['status']));

        $calendar->activity_id = null;
        $calendar->date_id = null;
        $calendar->time_id = null;
        $calendar->status = null;

        $this->assertFalse($calendar->validate(['activity_id']));
        $this->assertFalse($calendar->validate(['date_id']));
        $this->assertFalse($calendar->validate(['time_id']));
        $this->assertTrue($calendar->validate(['status']));



    }
    public function testSaveAndRead()
    {

        $calendar = $this->createCalendar();
        $wasSaveSuccessfull = $calendar->save();

        $this->assertTrue($wasSaveSuccessfull);

        $calendarFromDatabase = Calendar::find()->where(['activity_id' => $this->activity->id])
            ->one();
        $this->assertNotNull($calendarFromDatabase);
    }
    public function testUpdate(){
        $calendar = Calendar::find()->where(['id'=> $this->calendar->id])->one();
        $newName = 'NEW not null activity name';

        $calendar->status = "0";
        $calendar->save();

        $calendarFromDatabase = Calendar::find()->where(['id'=> $this->calendar->id])->one();
        $this->assertNotNull($calendarFromDatabase);

    }
    public function testDelete(){

        $calendar = $this->createCalendar();
        $this->assertTrue($calendar->save());

        $calendarFromDatabase = Calendar::findOne($calendar->id);
        $this->assertNotNull($calendarFromDatabase);

        $calendarFromDatabase->delete();
        $pessoaAfterDeletion = Calendar::findOne($calendar->id);
        $this->assertNull($pessoaAfterDeletion);
    }
    public function testValidCalendar(){
        $calendar = $this->createCalendar();
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
        $activity->date = ["0" => $this->date->date];
        $activity->hour = ["0" => $this->time->id];
        $activity->user_id = $this->user->id;

        return $activity;
    }
    public function createTime(){
        $time = new Time();
        $time->hour = self::VALID_HOUR;

        return $time;
    }

    public function createDate(){
        $date = new Date();
        $date->date = self::VALID_DATE;

        return $date;
    }
    public function createCalendar(){
        $calendar = new Calendar();
        $calendar->activity_id = $this->activity->id;
        $calendar->date_id = $this->date->id;
        $calendar->time_id = $this->time->id;
        $calendar->status = "1";

        return $calendar;
    }
}