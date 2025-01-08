<?php

/* @var $this yii\web\View */

/* @var $user common\models\User */

use common\models\Booking;

$this->title = 'Starter Page For ' . $role . " " . $user->username;
?>
<?php if ($role == "admin") { ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Total Invoiced',
                    'number' => $totalInvoiced . " €",
                    'icon' => 'far fa-money-bill-alt',
                ]) ?>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Today Invoiced',
                    'number' => $dayInvoiced . " €",
                    'icon' => 'far fa-money-bill-alt',
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Employees',
                    'number' => $numEmployees,
                    'icon' => 'far fa-user',
                ]) ?>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Sales For This Seller',
                    'number' => $numSalesSeller,
                    'theme' => $numSalesSeller == 0 ? 'warning' : 'success',
                    'icon' => $numSalesSeller == 0 ? 'far fa-times-circle' : 'far fa-check-circle',
                ]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Activity Categories available',
                    'number' => $numCategories,
                    'icon' => 'far fa-bookmark',
                ]) ?>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <?= $infoBox = \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Invoices',
                    'number' => $numInvoices,
                    'theme' => 'success',
                    'icon' => 'far fa-thumbs-up',

                ]) ?>
            </div>
        </div>
        <div class="row">
        </div>
        <div class="row">
            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Total Suppliers',
                    'number' => $numSuppliers,
                    'icon' => 'far fa-user',
                ]) ?>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Total Managers',
                    'number' => $numManagers,
                    'icon' => 'far fa-user',
                ]) ?>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Total Sellers',
                    'number' => $numSellers,
                    'icon' => 'far fa-user',
                ]) ?>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Total Guides',
                    'number' => $numGuides,
                    'icon' => 'far fa-user',
                ]) ?>
            </div>
            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Registered Clients',
                    'number' => $numClients,
                    'icon' => 'fas fa-user-plus',
                    'theme' => 'success',
                ]) ?>
            </div>
        </div>
    </div>
<?php } ?>
<?php if ($role == "supplier") { ?>
    <div class="row">
        <div class="col-md-4 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Shop Invoiced',
                'number' => $shopInvoiced . " €",
                'icon' => 'far fa-money-bill-alt',
            ]) ?>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Today Invoiced',
                'number' => $dayInvoiced . " €",
                'icon' => 'far fa-money-bill-alt',
            ]) ?>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Employees',
                'number' => $numEmployees,
                'icon' => 'far fa-user',
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Activity Categories available',
                'number' => $numCategories,
                'icon' => 'far fa-bookmark',
            ]) ?>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <?= $infoBox = \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Invoices',
                'number' => $numInvoices,
                'theme' => 'success',
                'icon' => 'far fa-thumbs-up',

            ]) ?>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <?= $infoBox = \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Sales For this shop',
                'number' => $numSalesShop,
                'theme' => 'success',
                'icon' => 'far fa-thumbs-up',

            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Local Shops',
                'number' => $numLocalShops,
                'icon' => 'far fa-building',
            ]) ?>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Total Sellers',
                'number' => $numSellers,
                'icon' => 'far fa-user',
            ]) ?>
        </div>
    </div>
    <div class="row">
        <?php foreach ($activities as $activity): ?>
            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Bookings for Activity: ' . $activity['name'],
                    'number' => Booking::getBookingsForActivityCount($activity['id']),
                    'icon' => 'fas fa-calendar-check',
                    'theme' => 'gradient-info',
                ]) ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php } ?>
<?php if ($role == "manager") { ?>
    <div class="row">
        <div class="col-md-4 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Shop Invoiced',
                'number' => $shopInvoiced . " €",
                'icon' => 'far fa-money-bill-alt',
            ]) ?>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Today Invoiced',
                'number' => $dayInvoiced . " €",
                'icon' => 'far fa-money-bill-alt',
            ]) ?>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <?= $infoBox = \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Invoices',
                'number' => $numInvoices,
                'theme' => 'success',
                'icon' => 'far fa-thumbs-up',

            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 col-sm-6 col-12">
            <?= $infoBox = \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Sales For this shop',
                'number' => $numSalesShop,
                'theme' => 'success',
                'icon' => 'far fa-thumbs-up',

            ]) ?>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Total Sellers',
                'number' => $numSellers,
                'icon' => 'far fa-user',
            ]) ?>
        </div>
    </div>
    <div class="row">
        <?php foreach ($activities as $activity): ?>
            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Bookings for Activity: ' . $activity['name'],
                    'number' => Booking::getBookingsForActivityCount($activity['id']),
                    'icon' => 'fas fa-calendar-check',
                    'theme' => 'gradient-info',
                ]) ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php } ?>
<?php if ($role == "salesperson") { ?>
    <div class="row">
        <div class="col-md-4 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Sales For This Seller',
                'number' => $numSalesSeller,
                'theme' => $numSalesSeller == 0 ? 'warning' : 'success',
                'icon' => $numSalesSeller == 0 ? 'far fa-times-circle' : 'far fa-check-circle',
            ]) ?>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Today Invoiced',
                'number' => $dayInvoiced . " €",
                'icon' => 'far fa-money-bill-alt',
            ]) ?>
        </div>
        <div class="col-md-4 col-sm-6 col-12">
            <?= \hail812\adminlte\widgets\InfoBox::widget([
                'text' => 'Registered Clients',
                'number' => $numClients,
                'icon' => 'fas fa-user-plus',
                'theme' => 'success',
            ]) ?>
        </div>
    </div>
<?php } ?>
<?php if ($role == "guide") { ?>
    <div class="row">
        <?php foreach ($activities as $activity): ?>
            <div class="col-md-4 col-sm-6 col-12">
                <?= \hail812\adminlte\widgets\InfoBox::widget([
                    'text' => 'Bookings for Activity: ' . $activity['name'],
                    'number' => Booking::getBookingsForActivityCount($activity['id']),
                    'icon' => 'fas fa-calendar-check',
                    'theme' => 'gradient-info',
                ]) ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php } ?>