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

        //Note: not every Class has a permission because its dependent on other classes to be created ( Time, Ticket, Invoice, Calendar, Date, Picture can't be CRUD )

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

        //Review Permissions
        $createReview = $auth->createPermission('createReview');
        $createReview->description = 'Create an review';
        $auth->add($createReview);

        $updateReview = $auth->createPermission('updateReview');
        $updateReview->description = 'Update an review';
        $auth->add($updateReview);

        $viewReview = $auth->createPermission('viewReview');
        $viewReview->description = 'View an review';
        $auth->add($viewReview);

        $deleteReview = $auth->createPermission('deleteReview');
        $deleteReview->description = 'Delete an review';
        $auth->add($deleteReview);

        //RoleRegister Permissions ( User Creation in backend )
        $createUserWithRole = $auth->createPermission('createUserWithRole');
        $createUserWithRole->description = 'Create an user with Role';
        $auth->add($createUserWithRole);

        $updateUserWithRole = $auth->createPermission('updateUserWithRole');
        $updateUserWithRole->description = 'Update an user with Role';
        $auth->add($updateUserWithRole);

        $viewUserWithRole = $auth->createPermission('viewUserWithRole');
        $viewUserWithRole->description = 'View an user with Role';
        $auth->add($viewUserWithRole);

        $deleteUserWithRole = $auth->createPermission('deleteUserWithRole');
        $deleteUserWithRole->description = 'Delete an user with Role';
        $auth->add($deleteUserWithRole);

        //Sale Permissions and bookings ( It is done at the same time)
        $createSaleAndBooking = $auth->createPermission('createSaleAndBooking');
        $createSaleAndBooking->description = 'Create a sale and booking';
        $auth->add($createSaleAndBooking);

        $updateSaleAndBooking = $auth->createPermission('updateSaleAndBooking');
        $updateSaleAndBooking->description = 'Update a sale and booking';
        $auth->add($updateSaleAndBooking);

        $viewSaleAndBooking = $auth->createPermission('viewSaleAndBooking');
        $viewSaleAndBooking->description = 'View a sale and booking';
        $auth->add($viewSaleAndBooking);

        $deleteSaleAndBooking = $auth->createPermission('deleteSaleAndBooking');
        $deleteSaleAndBooking->description = 'Delete a sale and booking';
        $auth->add($deleteSaleAndBooking);

        // Cart Permissions
        $createCart = $auth->createPermission('createCart');
        $createCart->description = 'Create a Cart';
        $auth->add($createCart);

        $updateCart = $auth->createPermission('updateCart');
        $updateCart->description = 'Update a Cart';
        $auth->add($updateCart);

        $viewCart = $auth->createPermission('viewCart');
        $viewCart->description = 'View a Cart';
        $auth->add($viewCart);

        $deleteCart = $auth->createPermission('deleteCart');
        $deleteCart->description = 'Delete a Cart';
        $auth->add($deleteCart);

        //Calendar permissions ( Closing and Opening dates for activities )


        // Define roles and their permissions
        $client = $auth->createRole('client');
        $auth->add($client);
        $auth->addChild($client, $viewActivity);
        $auth->addChild($client, $viewReview);
        $auth->addChild($client, $createReview);
        $auth->addChild($client, $updateReview);
        $auth->addChild($client, $deleteReview);
        $auth->addChild($client, $viewCart);
        $auth->addChild($client, $createCart);
        $auth->addChild($client, $updateCart);
        $auth->addChild($client, $deleteCart);

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
        $auth->addChild($supplier, $viewReview);
        $auth->addChild($supplier, $createReview);
        $auth->addChild($supplier, $updateReview);
        $auth->addChild($supplier, $deleteReview);
        $auth->addChild($supplier, $viewUserWithRole);
        $auth->addChild($supplier, $createUserWithRole);
        $auth->addChild($supplier, $updateUserWithRole);
        $auth->addChild($supplier, $deleteUserWithRole);
        $auth->addChild($supplier, $viewSaleAndBooking);
        $auth->addChild($supplier, $createSaleAndBooking);
        $auth->addChild($supplier, $updateSaleAndBooking);
        $auth->addChild($supplier, $deleteSaleAndBooking);

        $manager = $auth->createRole('manager');
        $auth->add($manager);
        $auth->addChild($manager, $viewActivity);
        $auth->addChild($manager, $updateShop);
        $auth->addChild($manager, $viewShop);
        $auth->addChild($manager, $viewReview);
        $auth->addChild($manager, $createReview);
        $auth->addChild($manager, $updateReview);
        $auth->addChild($manager, $deleteReview);
        $auth->addChild($manager, $viewUserWithRole);
        $auth->addChild($manager, $viewSaleAndBooking);
        $auth->addChild($manager, $createSaleAndBooking);
        $auth->addChild($manager, $updateSaleAndBooking);
        $auth->addChild($manager, $deleteSaleAndBooking);

        $salesperson = $auth->createRole('salesperson');
        $auth->add($salesperson);
        $auth->addChild($salesperson, $viewActivity);
        $auth->addChild($salesperson, $viewShop);
        $auth->addChild($salesperson, $viewReview);
        $auth->addChild($salesperson, $createReview);
        $auth->addChild($salesperson, $updateReview);
        $auth->addChild($salesperson, $deleteReview);
        $auth->addChild($salesperson, $viewUserWithRole);
        $auth->addChild($salesperson, $viewSaleAndBooking);
        $auth->addChild($salesperson, $createSaleAndBooking);
        $auth->addChild($salesperson, $updateSaleAndBooking);
        $auth->addChild($salesperson, $deleteSaleAndBooking);

        $guide = $auth->createRole('guide');
        $auth->add($guide);
        $auth->addChild($guide, $viewActivity);
        $auth->addChild($guide, $viewReview);
        $auth->addChild($guide, $createReview);
        $auth->addChild($guide, $updateReview);
        $auth->addChild($guide, $deleteReview);
        $auth->addChild($guide, $viewSaleAndBooking);

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
        $auth->addChild($admin, $viewReview);
        $auth->addChild($admin, $createReview);
        $auth->addChild($admin, $updateReview);
        $auth->addChild($admin, $deleteReview);
        $auth->addChild($admin, $viewUserWithRole);
        $auth->addChild($admin, $createUserWithRole);
        $auth->addChild($admin, $updateUserWithRole);
        $auth->addChild($admin, $deleteUserWithRole);
        $auth->addChild($admin, $viewSaleAndBooking);
        $auth->addChild($admin, $createSaleAndBooking);
        $auth->addChild($admin, $updateSaleAndBooking);
        $auth->addChild($admin, $deleteSaleAndBooking);
        $auth->addChild($admin, $viewCart);
        $auth->addChild($admin, $createCart);
        $auth->addChild($admin, $updateCart);
        $auth->addChild($admin, $deleteCart);
    }
}