<?php
namespace backend\tests\functional;
use backend\tests\FunctionalTester;
use common\models\Activity;
use common\models\User;
use common\models\UserExtra;


class CreateActivityCest
{
    private const PASSWORD = "123123123";
    protected $user;
    protected $userextra;

    public function _before(FunctionalTester $I)
    {
        $this->user = $this->createValidUser(true);
        $this->userextra = $this->createUserExtra();
    }

    public function firstToTest(FunctionalTester $I){
        //$I->amOnRoute('/');
        $I->amOnPage('/site/login');
        $I->see('Sign in to start your session');

        $I->fillField('LoginForm[username]', 'aaa');
        $I->fillField('LoginForm[password]', '123456');
        $I->click('Sign In');
        $I->seeValidationError('Incorrect username or password.');

        $I->fillField('LoginForm[username]', "Test");
        $I->fillField('LoginForm[password]', "123123123");
        $I->click('Sign In');
        $I->see('Starter Page');

        $I->see('Create activity');
        $I->click('Create activity');
        $I->see("Create Activities");
        $I->fillField('Name', 'Test Activity name');
        $I->fillField('Description', 'Test Description  Activity name');
        //$I->fillField('Activity[photoFile]', 'testFile.jpg');
        $I->attachFile('#photoFile', 'testFile.jpg');

        $I->selectOption('Activity[category_id]','1');
        $I->fillField('Activity[maxpax]', '20');
        $I->fillField('Activity[priceperpax]', '20');
        $I->fillField('Activity[address]', 'Test address n32 1000-100 Lisboa');
        $I->fillField('Activity[date][]', '02-20-2025');
        $I->selectOption('Activity[hour][]', '20');
        $I->see('No image uploaded yet.');

        $I->click('Save');


        // Update activity

        $I->see('Activities');
        $I->dontSee('Create Activities');

//        $I->click("View");
//        $I->click('Update');
//        $I->fillField('input[name="Activity[name]"][value="Test Activity name"]', 'Test Activity name Update');
//        $I->click('Save');
//
//        $I->see('Activities');
//
//        $I->see('Manage Calendar');
//        $I->click('Manage Calendar');
//        $I->checkOption(['css' => '.btn-check']);

    }
    private function createValidUser(bool $save = false)
    {
        $user = new User();
        $user->username = 'Test';
        $user->email = 'test@test.com';
        $user->setPassword(self::PASSWORD);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->status = User::STATUS_ACTIVE; // Para nao enviar email de confirmação


        if($save){
            $user->save(false);

            $auth = \Yii::$app->authManager;
            $clientRole = $auth->getRole('supplier');
            $auth->assign($clientRole, $user->getId());

            echo "user id " . $user->id . " role " . $clientRole->name . "\n";

        }
        return $user;
    }

    private function createUserExtra()
    {
        $userExtra = new UserExtra();
        $userExtra->phone = "912222222";
        $userExtra->user_id = $this->user->id;
        $userExtra->address = "Avenida do Alfredo Junior";
        $userExtra->nif = "213212290";
        $userExtra->supplier = $this->user->id;
        $userExtra->save(false);

        return $userExtra;
    }
}
