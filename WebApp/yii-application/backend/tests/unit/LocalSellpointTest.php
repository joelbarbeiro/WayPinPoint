<?php
namespace backend\tests;

use backend\models\Localsellpoint;
use common\models\User;

class LocalSellpointTest extends \Codeception\Test\Unit
{
    /**
     * @var \backend\tests\UnitTester
     */
    protected $tester;
    protected $user;
    private const STRING_199_CHARS = 'nP8maws1lTqjEcNjaWJIrdEIIbIbcgTkKeeJnCXtRyuRRdKMpMTaIbcQjeOzN0eodkPcfjC4LwVO7jCuyAEc2kpCApfdwzP01iSGGDwfdKLmCBmXypUWP7JEgEHquTL9BYZ6MLMfKJh7g6WgdnbkFwM4mDcXbZ6Ma3R3gFA47AEfm4ZKfFJTrrjpg31Ixx2KdlMzH9v
';
    private const STRING_402_CHARS = 'iwkbTqzWEuveVd9F0dUxI9R7i7jSGHENN39MHS21n2Axguc7jJ6z8WHpj7KscMnTI06mwT0hMDJ12lHcVt7N2b0B2Jg2jEFv5GNPlIXcmqn5k6efx6gKQHo2TWVrm98jXxzPJyyjCanbAVFPZ8Vk5vC9f8A4KZyq1NAT4BlbM6wYL8O1o5Z6cLfzJ6P3lNEPfDJHrfo868b8RQiqDlmdSeJkIQSLED6TNfNleojYSXUKH86nyW0AvhieSgj8INLaP3A0ZyhMu5LmAj4WYPfy6450popdzm3EYimHL0LSUiJAxAz8Mj6Fr7NlWdsVJgG3qJ96MJFdSFqCChc2TEJ4bPNY93SI9TLvTPDjLmjbH9BtEDt7CkdZPs6gTevWXufXKCsonCf0LnCmqwNIRY

';

    protected function _before()
    {
        $this->user = $this->createUser();
        $this->user->save();

        $localSellPoint = $this->createLocalShop();
        $localSellPoint->save();
    }

    protected function _after()
    {
    }

    // tests
    public function testValidCreation()
    {
        $localShop = $this->createLocalShop();

        $this->assertTrue($localShop->validate('id'));
        $this->assertTrue($localShop->validate('name'));
        $this->assertTrue($localShop->validate('address'));
        $this->assertTrue($localShop->validate('user_id'));

        $localShop->name = self::STRING_402_CHARS;
        $localShop->address = self::STRING_402_CHARS;
        $localShop->user_id = -1;

        $this->assertFalse($localShop->validate('name'));
        $this->assertFalse($localShop->validate('address'));
        $this->assertFalse($localShop->validate('user_id'));

        $localShop->name = null;
        $localShop->address = null;

        $this->assertFalse($localShop->validate('name'));
        $this->assertFalse($localShop->validate('address'));
    }

    public function testSaveAndRead()
    {
        $localShop = $this->createLocalShop();

        $localShop->name = "New Name";
        $localShop->address = "New Address";
        $localShop->save();

        $localShopFromDatabase = Localsellpoint::findOne(['name' => 'New Name']);
        $this->assertNotNull($localShopFromDatabase);
        $this->assertEquals("New Address", $localShopFromDatabase->address);
    }

    public function testUpdateAndRead()
    {
        $localShopFromDatabase = Localsellpoint::findOne(['name'=> "Local Shop"]);
        $localShopFromDatabase->name = "New Name";
        $localShopFromDatabase->address = "New Address";
        $localShopFromDatabase->save();

        $localShopAlteredFromDatabase = Localsellpoint::findOne($localShopFromDatabase->id);
        $this->assertNotNull($localShopAlteredFromDatabase);
        $this->assertEquals("New Address", $localShopAlteredFromDatabase->address);
    }

    public function testDeleteAndRead(){
        $LocalShopToDelete = Localsellpoint::findOne(['name' => "Local Shop"]);
        $result =  $LocalShopToDelete->delete();
        $this->assertEquals(1,$result);

        $LocalShopFromDatabase = Localsellpoint::findOne(['name' => "Local Shop"]);
        $this->assertNull($LocalShopFromDatabase);
    }

    public function createLocalShop()
    {
        $localShop = new Localsellpoint();
        $localShop->name = "Local Shop";
        $localShop->address = self::STRING_199_CHARS;
        $localShop->user_id = $this->user->id;
        return $localShop;
    }

    public function createUser()
    {
        $user = new User();
        $user->username = "user test";
        $user->email = "usertest@gmail.com";
        $user->password = "usertest123123";

        return $user;
    }
}