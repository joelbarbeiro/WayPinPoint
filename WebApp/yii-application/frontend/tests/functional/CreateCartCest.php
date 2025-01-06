<?php

namespace frontend\tests\functional;

use common\models\User;
use common\models\UserExtra;
use frontend\tests\FunctionalTester;


class CreateCartCest
{
    protected $user;
    protected $userextra;

    public function _before(FunctionalTester $I)
    {
        $this->user = $this->createValidUser(true);
        $this->userextra = $this->createUserExtra();

    }

    // tests


    public function checkOut(FunctionalTester $I)
    {
        $I->amOnRoute('/');
        $I->see('Activities');
        $I->click('Login');
        $I->fillField('Username', 'Test');
        $I->fillField('Password', '123123123');
        $I->click('login-button');
        $I->see('Activities');
        $I->see('Buy');

        $I->click('Buy');
        $I->see('Number of tickets ');
        $I->fillField('Cart[quantity]', '1');
        $I->click('Save');
        $I->see('Shopping Cart');
        $I->click('Checkout');
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
