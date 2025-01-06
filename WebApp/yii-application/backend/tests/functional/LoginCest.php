<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\models\User;
use common\fixtures\UserFixture;
use frontend\tests\functional\MainTester as FunctionalTesterRoute;

/**
 * Class LoginCest
 */
class LoginCest
{
    /**
     * Load fixtures before db transaction begin
     * Called in _before()
     * @see \Codeception\Module\Yii2::_before()
     * @see \Codeception\Module\Yii2::loadFixtures()
     * @return array
     */
    public function _fixtures()
    {
        return [
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'login_data.php'
            ]
        ];
    }
    public function _before(\frontend\tests\FunctionalTester $I)
    {
        $this->createValidUser(true);
    }

    // tests
    public function firstToTest(FunctionalTester $I)
    {
        $I->amOnRoute('site/login'); // $I->amOnRoute('/');
        $I->see('Sign in to start your session');

        $I->click('Sign In');
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
