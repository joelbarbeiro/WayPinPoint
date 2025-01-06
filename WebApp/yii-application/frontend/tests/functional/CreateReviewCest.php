<?php
namespace frontend\tests\functional;
use common\models\User;
use frontend\tests\FunctionalTester;
class CreateReviewCest
{
    public function _before(FunctionalTester $I)
    {
        $this->createValidUser(true);
    }

    // tests
    public function firstToTest(FunctionalTester $I)
    {
        $I->amOnRoute('site/login');
        $I->see('Activities');
        $I->seeLink('Login');

        $I->click('Login');
        $I->see('Please fill out the following fields to login:');

        $I->click('login-button');
        $I->seeValidationError('Username cannot be blank.');
        $I->seeValidationError('Password cannot be blank.');

        $I->fillField('LoginForm[username]', 'aaa');
        $I->fillField('LoginForm[password]', 'aaa');
        $I->click('login-button');
        $I->seeValidationError('Incorrect username or password.');

        $I->fillField('LoginForm[username]', 'Test');
        $I->fillField('LoginForm[password]', '123123123');
        $I->click('login-button');
        $I->see('Logout');

        $I->see('Review');
        $I->click('Review');
        $I->see('Create Review');
        $I->click('Create Review');

        $I->see('Score');
        $I->seeInSource('★★★');
        $I->selectOption('input[name="Review[score]"][value="3"]', '3');
        $I->fillField('Message', 'I Love This Activity');
        $I->click('Save');
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

        if($save){
            $user->save(false);
        }
        return $user;
    }
}
