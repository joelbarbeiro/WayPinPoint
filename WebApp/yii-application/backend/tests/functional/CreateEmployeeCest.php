<?php
namespace backend\tests\functional;
use backend\tests\FunctionalTester;
use common\models\User;
use common\models\UserExtra;

class CreateEmployeeCest
{
    public function _before(FunctionalTester $I)
    {
        $this->user = $this->createValidUser(true);
        $this->userextra = $this->createUserExtra(true);
    }

    // tests
    public function tryToTest(FunctionalTester $I)
    {
        $I->amOnRoute('site/login'); // $I->amOnRoute('/');
        $I->see('Sign in to start your session');

        $I->click('Sign In');
        $I->seeValidationError('Username cannot be blank.');
        $I->seeValidationError('Password cannot be blank.');

        $I->fillField('LoginForm[username]', 'aaa');
        $I->fillField('LoginForm[password]', 'aaa');
        $I->click('Sign In');
        $I->seeValidationError('Incorrect username or password.');

        $I->fillField('LoginForm[username]', 'Test1');
        $I->fillField('LoginForm[password]', '123123123');
        $I->click('Sign In');
        $I->see('Starter Page');

        $I->see('Add Local Shop');
        $I->click('Add Local Shop');

        $I->see('Create Local Shop');
        $I->fillField('Address', 'Local Street');
        $I->fillField('Name', 'My Local Shop');
        $I->see('Save');
        $I->click('Save');
        $I->see('Update');

        $I->see('User');
        $I->click('User');
        $I->see('Register an Employee');
        $I->click('Register an Employee');
        $I->see('Photo File');

        $I->fillField('Username', 'Employee');
        $I->fillField('Email', 'employee@mail.com');
        $I->fillField('Password', '123123123');
        $I->fillField('Phone', '915556661');
        $I->fillField('Address', 'Employee Street');
        $I->fillField('Nif', '456456789');
        $I->selectOption('RoleRegisterForm[localsellpoint]',"14"); // MUDAR SEMPRE PARA UM ACIMA
        $I->selectOption('RoleRegisterForm[role]',"manager");
        $I->see('Register User');
        $I->click('Register User');

        $I->see('Starter Page');
    }

    private function createValidUser(bool $save = false)
    {
        $user = new User();
        $user->username = 'Test1';
        $user->email = 'test1@test.com';
        $user->setPassword('123123123');
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->status = User::STATUS_ACTIVE; // Para nao enviar email de confirmação

        if ($save) {
            $user->save(false);

            $auth = \Yii::$app->authManager;
            $clientRole = $auth->getRole('supplier');
            $auth->assign($clientRole, $user->getId());

            echo "user id " . $user->id . " role " . $clientRole->name . "\n";

        }
        return $user;
    }

    private function createUserExtra(bool $save = false)
    {
        $userExtra = new UserExtra();
        $userExtra->phone = "912222222";
        $userExtra->user_id = $this->user->id;
        $userExtra->address = "Avenida do Alfredo Junior";
        $userExtra->nif = "213212296";
        if ($save) {
            $userExtra->save(false);
        }

        return $userExtra;
    }
}
