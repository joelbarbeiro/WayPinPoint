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
    private const STRING_80_CHARS = 'jofxabzawssmlxopymkbkglwunpidwopvpsiabrcrdctieetcahewubomqcseojxbhbzuijixsyofznn';
    private const INVALID_PRODUCT_ID = 0;
    private const INVALID_USER_ID = 0;
    private const INVALID_QUANTITY = -1;
    private const INVALID_CART_ID = 0;
    private const INVALID_STATUS = 3;
    private const INVALID_CALENDAR_ID = 0;
    private const VALID_PRODUCT_ID = 1;
    private const VALID_USER_ID = 1;
    private const VALID_QUANTITY = 1;
    private const VALID_STATUS = 0;
    private const VALID_CALENDAR_ID = 1;
    protected UnitTester $tester;

    protected function _before()
    {
        $user = $this->createValidUser();
        $user->save();

        $activity = $this->createValidActivity($user);
        $activity->save();

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
        $this->assertEquals(self::VALID_USER_ID, $cartFromDatabase->user_id);
        $this->assertEquals(self::VALID_QUANTITY, $cartFromDatabase->quantity);
        $this->assertEquals(self::VALID_STATUS, $cartFromDatabase->status);
    }

    private function createValidCart()
    {
        $cart = new Cart();
        $cart->product_id = self::VALID_PRODUCT_ID;
        $cart->user_id = self::VALID_USER_ID;
        $cart->quantity = self::VALID_QUANTITY;
        $cart->status = self::VALID_STATUS;
        $cart->calendar_id = self::VALID_CALENDAR_ID;
        return $cart;
    }

    private function createValidUser()
    {
        $user = new User();
        $user->id = 1;
        $user->username = "pedro";
        $user->email = "pedro@gmail.com";
        $user->password = "pedro123123";
        return $user;
    }

    private function createValidActivity($user)
    {
        $category = new Category();
        $category->description = "Water Sports";
        $category->save();
        $activity = new Activity();
        $activity->name = "Surf";
        $activity->address = "Rua da ponta";
        $activity->photo = "c:/file.jpg";
        $activity->user_id = $user->id;
        $activity->maxpax = 3;
        $activity->status = 1;
        $activity->description = "Standard description";
        $activity->priceperpax = 10;
        $activity->category_id = $category->id;
        $activity->date = ["0=>2025-02-03"];
        $activity->hour = ["0=>23"];
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



}