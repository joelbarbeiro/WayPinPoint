<?php
namespace backend\tests\functional;
use backend\tests\FunctionalTester;
class CreateActivityCest
{
    private const BACKEND_URL = "site/login";
    private $user;
    public function _before(FunctionalTester $I)
    {
        $I->amOnRoute("site/index");
    }

    // tests
    public function tryToTest(FunctionalTester $I)
    {
    }
    public function createActivity(FunctionalTester $I)
    {
        $I->amOnPage('/activity/create'); // Navigate to the activity creation page
        $I->see('Create Activity', 'h1'); // Assert that the header is displayed
        $I->fillField('Name', 'Test Activity'); // Replace with the actual field names
        $I->fillField('Description', 'Test Activity Description');
        $I->click('Save'); // Adjust the button text as needed
        $I->see('Activity has been created'); // Assert that creation was successful
    }

    private function login(FunctionalTester $I)
    {

        $I->amOnPage(self::BACKEND_URL);
        $I->see('You have access to backend.');
        $I->seeLink('Login');
        $I->see('Sign in to start your session', 'p');
        $I->fillField('Username', 'admin'); // Replace with actual username field name
        $I->fillField('Password', 'admin_password'); // Replace with actual password field name
        $I->click('Login'); // Click the login button
        $I->see('Dashboard', 'h1'); // Verify successful login by checking for a post-login element
    }
}
