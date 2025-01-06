<?php

namespace backend\tests\functional;

use backend\tests\FunctionalTester;
use common\models\User;

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

    public function _before(FunctionalTester $I)
    {
        $this->createValidUser(true);
    }

    public function firstToTest(FunctionalTester $I){
        $I->amOnRoute('/');
        //$I->amOnPage('/');
        $I->see('Sign in to start your session');

        $I->fillField('LoginForm[username]', 'aaa');
        $I->fillField('LoginForm[password]', '123456');
        $I->click('Sign in');
        $I->see('Sign in to start your session');

        $I->fillField('LoginForm[username]', 'Test');
        $I->fillField('LoginForm[password]', '123123123');
        $I->click('Sign in');
        $I->see('Starter Page');

        $I->see('Create activity');
        $I->click('Create activity');
        $I->fillField('input[name="Activity[name]"][value="Test Activity name"]', 'Test Activity name');
        $I->fillField('input[name="Activity[description]"][value="Test Activity description"]', 'Test Description  Activity name');
        $I->fillField('input[name="Activity[photoFile]"][value="c:\testFile.jpg"]', 'c:\testFile.jpg');
        $I->selectOption('input[name="Activity[category]"][value="Hiking"]', 'Hiking');
        $I->fillField('input[name="Activity[maxpax]"][value="20"]', '20');
        $I->fillField('input[name="Activity[priceperpax]"][value="20"]', '20');
        $I->fillField('input[name="Activity[address]"][value="Test address n32 1000-100 Lisboa"]',
            'Test address n32 1000-100 Lisboa');
        $I->selectOption('input[name="Activity[date]"][value="2025-02-20"]', '2025-02-20');
        $I->selectOption('input[name=Activity[hour]"][value="20"]', '20');
        $I->see('Add activity');
        $I->click('Save');

        $I->see('Activities');

        // Update activity

        $I->see('View');
        $I->click('View');
        $I->click('Update');
        $I->fillField('input[name="Activity[name]"][value="Test Activity name"]', 'Test Activity name Update');
        $I->click('Save');

        $I->see('Activities');

        $I->see('Manage Calendar');
        $I->click('Manage Calendar');
        $I->checkOption(['css' => '.btn-check']);

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

            $auth = \Yii::$app->authManager;

            var_dump($auth->getRole('supplier'));

            $clientRole = $auth->getRole('supplier');
            $auth->assign($clientRole, $user->getId());

            echo "user id " . $user->id . " role " . $clientRole->name . "\n";

        }
        return $user;
    }
}
