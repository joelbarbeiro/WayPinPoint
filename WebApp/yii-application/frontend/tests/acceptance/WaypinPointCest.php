<?php

namespace frontend\tests\acceptance;

use common\models\User;
use common\models\UserExtra;
use frontend\tests\AcceptanceTester;

class WaypinPointCest
{
    protected $user;
    protected $userextra;
    public function before(AcceptanceTester $I)
    {

    }

    public function buyAndCheckout(AcceptanceTester $I)
    {

        $I->amOnPage('//');
        $I->see('Activities');
        $I->see('Signup');
        $I->click('Signup');
        $I->see('Username');
        $I->fillField('SignupForm[username]', 'Test');
        $I->see('Email');
        $I->fillField('SignupForm[email]', 'test@test.com');
        $I->see('Password');
        $I->fillField('SignupForm[password]','123123123');
        $I->see('Phone');
        $I->fillField('SignupForm[phone]','912912912');
        $I->see('Address');
        $I->fillField('SignupForm[address]', 'Test Address');
        $I->see('Nif');
        $I->fillField('SignupForm[nif]', '912912961');
        $I->click('signup-button');

        $I->waitForText('Activities');
        $I->click('Login');
        $I->fillField('Username', 'Test');
        $I->fillField('Password', '123123123');
        $I->click('login-button');
        $I->waitForText('Activities');
        $I->see('Activities');
        $I->see('Buy');
        $I->click('Buy');
        $I->see('Number of tickets ');
        $I->fillField('Cart[quantity]', '1');
        $I->click('Save');
        $I->waitForText('Shopping Cart');
        $I->see('Shopping Cart');
        $I->click('Checkout');
        $I->waitForText("Ticket and Receipt sent by Email! You can find them in your personal area as well to download");
        $I->see("Ticket and Receipt sent by Email! You can find them in your personal area as well to download");

        $I->see('Review');
        $I->click('Review');
        $I->waitForText('Create Review');
        $I->see('Create Review');
        $I->click('Create Review');
        $I->waitForText('Score');
        $I->see('Score');
        $I->seeInSource('★★★');
        $I->selectOption('input[name="Review[score]"][value="3"]', '3');
        $I->fillField('Message', 'I Love This Activity');
        $I->click('Save');
        $I->waitForText('Edit');
        $I->see('Edit');

    }

    private function createValidUser(bool $save = false)
    {
        $user = new User();
        $user->username = 'Test';
        $user->email = 'test@test.com';
        $user->setPassword('123123123');
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->status = User::STATUS_ACTIVE; // Para nao enviar email de confirmação

        if ($save) {
            $user->save(false);
        }
        return $user;
    }

    private function createUserExtra()
    {
        $userExtra = new UserExtra();
        $userExtra->phone = "912222222";
        $userExtra->user_id = $this->user->id;
        $userExtra->address = "Avenida do Alfredo Junior";
        $userExtra->nif = "213212211";
        $userExtra->save(false);
        return $userExtra;
    }


}
