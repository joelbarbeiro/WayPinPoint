<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        //Activities permissions
        $createActivity = $auth->createPermission('createActivity');
        $createActivity->description = 'Create an activity';
        $auth->add($createActivity);

        $updateActivity = $auth->createPermission('updateActivity');
        $updateActivity->description = 'Update an activity';
        $auth->add($updateActivity);

        $viewActivity = $auth->createPermission('viewActivity');
        $viewActivity->description = 'View an activity';
        $auth->add($viewActivity);

        $deleteActivity = $auth->createPermission('deleteActivity');
        $deleteActivity->description = 'Delete an activity';
        $auth->add($deleteActivity);

        //Shop permissions
        $createShop = $auth->createPermission('createShop');
        $createShop->description = 'Create a Local Shop';
        $auth->add($createShop);

        $updateShop = $auth->createPermission('updateShop');
        $updateShop->description = 'Update a Local Shop';
        $auth->add($updateShop);

        $viewShop = $auth->createPermission('viewShop');
        $viewShop->description = 'View a Local Shop';
        $auth->add($viewShop);

        $deleteShop = $auth->createPermission('deleteShop');
        $deleteShop->description = 'Delete a Local Shop';
        $auth->add($deleteShop);

        // Define roles
        $client = $auth->createRole('client');
        $auth->add($client);
        $auth->addChild($client, $viewActivity);

        $supplier = $auth->createRole('supplier');
        $auth->add($supplier);
        $auth->addChild($supplier, $viewActivity);
        $auth->addChild($supplier, $createActivity);
        $auth->addChild($supplier, $updateActivity);
        $auth->addChild($supplier, $deleteActivity);
        $auth->addChild($supplier, $createShop);
        $auth->addChild($supplier, $updateShop);
        $auth->addChild($supplier, $viewShop);
        $auth->addChild($supplier, $deleteShop);

        $manager = $auth->createRole('manager');
        $auth->add($manager);
        $auth->addChild($manager, $viewActivity);
        $auth->addChild($manager, $updateActivity);
        $auth->addChild($manager, $updateShop);
        $auth->addChild($manager, $viewShop);

        $salesperson = $auth->createRole('salesperson');
        $auth->add($salesperson);
        $auth->addChild($salesperson, $viewActivity);
        $auth->addChild($salesperson, $updateActivity);
        $auth->addChild($salesperson, $viewShop);

        $guide = $auth->createRole('guide');
        $auth->add($guide);
        $auth->addChild($guide, $viewActivity);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $updateActivity);
        $auth->addChild($admin, $createActivity);
        $auth->addChild($admin, $viewActivity);
        $auth->addChild($admin, $deleteActivity);
        $auth->addChild($admin, $client);
        $auth->addChild($admin, $supplier);
        $auth->addChild($admin, $manager);
        $auth->addChild($admin, $salesperson);
        $auth->addChild($admin, $guide);
        $auth->addChild($admin, $createShop);
        $auth->addChild($admin, $updateShop);
        $auth->addChild($admin, $viewShop);
        $auth->addChild($admin, $deleteShop);
    }
}