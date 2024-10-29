<?php

namespace console\controllers;
//namespace for advanced Project
use Yii;
use yii\console\Controller;

class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        // add "createPost" permission
        $createActivity = $auth->createPermission('createActivity');
        $createActivity->description = 'Create a activity';
        $auth->add($createActivity);

        // add "updateActivity" permission
        $updateActivity = $auth->createPermission('updateActivity');
        $updateActivity->description = 'Update Activity';
        $auth->add($updateActivity);

        // add "manageUsers" permission
        $manageUsers = $auth->createPermission('manageUsers');
        $manageUsers->description = 'Manage Users';
        $auth->add($manageUsers);

        $assignRoles = $auth->createPermission('assignRoles');
        $assignRoles->description = 'Assign roles to users';
        $auth->add($assignRoles);
        // add "client" role and give this role the "createPost" permission
        $client = $auth->createRole('client');
        $auth->add($client);
        $auth->addChild($client, $createActivity);
        $auth->addChild($client, $updateActivity);

        // add "admin" role and give this role the "updateActivity" permission
        // as well as the permissions of the "author" role
        $admin = $auth->createRole('admin');
        $auth->add($admin);
        $auth->addChild($admin, $manageUsers);
        $auth->addChild($admin, $assignRoles);
        $auth->addChild($admin, $client);
        // Assign roles to users. 1 and 2 are IDs returned by IdentityInterface::getId()
        // usually implemented in your User model.
        $auth->assign($client, 2);
        $auth->assign($admin, 1);
        // php yii rbac/init no fim disto para fazer assign disto tudo
    }
}