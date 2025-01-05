<?php

namespace common\tests;

use common\models\Activity;
use common\models\Cart;
use common\models\Category;
use common\models\User;
use yii\db\Exception;

class CartTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    private const VALID_NAME = 'Test activity';
    private const VALID_DESCRIPTION = 'Test activity description';
    private const VALID_INT = '10';
    private const VALID_ADDRESS = 'Test activity address nÂº 4 3 esq, 1500-000, Lisboa';
    private const VALID_PHOTO = 'c:\testFile.jpg';
    private const VALID_DATE = ['0' => '2025-01-01', '1' => '2025-02-01'];
    private const VALID_HOUR = ['0' => '10', '1' => '20'];
    private const INVALID_DATE = '2025-01-01';
    private const INVALID_HOUR = '12:00';
    private const STRING_80_CHARS = 'jofxabzawssmlxopymkbkglwunpidwopvpsiabrcrdctieetcahewubomqcseojxbhbzuijixsyofznn';
    private const INVALID_PRODUCT_ID = 0;
    private const INVALID_USER_ID = 0;
    private const INVALID_QUANTITY = -1; //Needs to be minimum 1
    private const INVALID_CART_ID = 0;
    private const INVALID_STATUS = 3; //Only allowed to be between 0 and 1
    private const INVALID_CALENDAR_ID = 0;
    private const VALID_PRODUCT_ID = 1;
    private const VALID_USER_ID = 1;
    private const VALID_QUANTITY = 1;
    private const VALID_STATUS = 0;
    private const VALID_CALENDAR_ID = 1;
    protected UnitTester $tester;
    protected $user;
    protected $category;
    protected $activity;

    protected function _before()
    {
        $this->user = $this->createValidUser();
        $this->user->save();


        $this->category = $this->createCategory();
        $this->category->save();


        $this->activity = $this->createValidActivity();
        $this->activity->save();


        $cart = $this->createValidCart();
        $cart->save();

    }

    protected function _after()
    {
    }

    // tests
    public function testSomeFeature()
    {
    }

    public function testValidations()
    {
        $cart = $this->createValidCart();
        $cart->product_id = self::INVALID_PRODUCT_ID;
        $cart->user_id = self::INVALID_USER_ID;
        $cart->quantity = self::INVALID_QUANTITY;
        $cart->calendar_id = self::INVALID_CALENDAR_ID;
        $cart->status = self::INVALID_STATUS;

        $this->assertFalse($cart->validate(['product_id']));
        $this->assertFalse($cart->validate(['user_id']));
        $this->assertFalse($cart->validate(['quantity']));
        $this->assertFalse($cart->validate(['calendar_id']));

        $cart->product_id = null;
        $cart->user_id = null;
        $cart->quantity = null;
        $cart->calendar_id = null; //TODO INFORMAR O CLIENTE QUE A QUANTIDADE TEM DE SER NO MINIMO 1
        $cart->status = null;

        $this->assertFalse($cart->validate(['product_id']));
        $this->assertFalse($cart->validate(['user_id']));
        $this->assertFalse($cart->validate(['quantity']));
        $this->assertFalse($cart->validate(['calendar_id']));

    }

    public function testSaveAndRead()
    {
        $cart = $this->createValidCart();
        $wasSavedSuccessfully = $cart->save(false);

        $this->assertTrue($wasSavedSuccessfully);

        $cartFromDatabase = Cart::find()->where(['product_id' => self::VALID_PRODUCT_ID])->one();
        $this->assertNotNull($cartFromDatabase);
        $this->assertEquals(self::VALID_PRODUCT_ID, $cartFromDatabase->product_id);
        $this->assertEquals( self::VALID_QUANTITY, $cartFromDatabase->quantity);
        $this->assertEquals(self::VALID_STATUS, $cartFromDatabase->status);
    }

    private function createValidCart()
    {
        $cart = new Cart();
        $cart->product_id = self::VALID_PRODUCT_ID;
        $cart->user_id = $this->user->id;
        $cart->quantity = self::VALID_QUANTITY;
        $cart->status = self::VALID_STATUS;
        $cart->calendar_id = self::VALID_CALENDAR_ID;
        return $cart;
    }

    private function createValidUser()
    {
        $user = new User();
        $user->username = "pedro";
        $user->email = "pedro@gmail.com";
        $user->password = "pedro123123";

        return $user;
    }
    public function createCategory(){
        $category = new Category();
        $category->description = "Test category";

        return $category;
    }
    private function createValidActivity()
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

    public function testUpdate()
    {
        $cart = $this->createValidCart();
        $cart['quantity'] = self::INVALID_QUANTITY;
        $wasSavedSuccessfully = $cart->save(false);
        $this->assertTrue($wasSavedSuccessfully, "Cart should not be saved due to invalid quantity");
    }

    public function testInvalidStatus()
    {
        $cart = $this->createValidCart();
        $cart['status'] = self::INVALID_STATUS;
        $wasSavedSuccessfully = $cart->save(false);
        $this->assertTrue($wasSavedSuccessfully, "Cart should not be saved due to invalid status");
    }

    public function testDelete()
    {
        $cart = $this->createValidCart();
        $cart->save(false);
        $cartId = $cart->id;
        $cartFromDatabase = Cart::findOne($cartId);
        $this->assertNotNull($cartFromDatabase, "Cart should exist in the database before deletion.");

        $wasDeletedSuccessfully = $cart->delete();
        $this->assertEquals(1, $wasDeletedSuccessfully, "Cart should be deleted successfully.");
    }

    public function testStatus()
    {
        $cart = $this->createValidCart();
        $cart['status'] = 1;
        $wasSavedSuccessfully = $cart->save(false);
        $this->assertTrue($wasSavedSuccessfully, "Cart should not be saved due to invalid status");
    }

    public function createInvalidCart()
    {
        $cart = new Cart();
        $cart->product_id = self::INVALID_PRODUCT_ID;
        $cart->user_id = self::INVALID_USER_ID;
        $cart->quantity = self::INVALID_QUANTITY;
        $cart->calendar_id = self::INVALID_CALENDAR_ID;
        return $cart;
    }
    public function testInvalidCart()
    {
        $cart = $this->createInvalidCart();
        $this->assertFalse($cart->validate(['product_id']), 'Validation should fail for product_id');
        $this->assertFalse($cart->validate(['user_id']), 'Validation should fail for user_id');
        $this->assertFalse($cart->validate(['quantity']), 'Validation should fail for quantity');
        $this->assertFalse($cart->validate(['calendar_id']), 'Validation should fail for calendar_id');

        $wasSavedSuccessfully = $cart->save();
        $this->assertFalse($wasSavedSuccessfully, "Cart should not be saved due to invalid status");

    }
}