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

        // Define permissions
        $createActivity = $auth->createPermission('createActivity');
        $createActivity->description = 'Create an activity';
        $auth->add($createActivity);

        $updateActivity = $auth->createPermission('updateActivity');
        $updateActivity->description = 'Update an activity';
        $auth->add($updateActivity);

        $viewActivity = $auth->createPermission('viewActivity');
        $viewActivity->description = 'View an activity';
        $auth->add($viewActivity);

        // Define roles
        $client = $auth->createRole('client');
        $auth->add($client);
        $auth->addChild($client, $viewActivity);

        $supplier = $auth->createRole('supplier');
        $auth->add($supplier);
        $auth->addChild($supplier, $viewActivity);
        $auth->addChild($supplier, $createActivity);
        $auth->addChild($supplier,$updateActivity);

        $manager = $auth->createRole('manager');
        $auth->add($manager);
        $auth->addChild($manager, $viewActivity);
        $auth->addChild($manager, $updateActivity);

        $salesperson = $auth->createRole('salesperson');
        $auth->add($salesperson);
        $auth->addChild($salesperson, $viewActivity);
        $auth->addChild($salesperson, $updateActivity);

        $guide = $auth->createRole('guide');
        $auth->add($guide);
        $auth->addChild($guide, $viewActivity);

        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $updateActivity);
        $auth->addChild($admin, $client);
        $auth->addChild($admin, $supplier);
        $auth->addChild($admin, $manager);
        $auth->addChild($admin, $salesperson);
        $auth->addChild($admin, $guide);
    }
}