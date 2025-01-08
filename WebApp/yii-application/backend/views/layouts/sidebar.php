<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\backend\assets $assetDir */

$logo = Url::to('@web/img/logo/waypinpoint.png');
$imgUserPath = Url::to('@web/img/user/' . Yii::$app->user->id . '/');
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?=\yii\helpers\Url::home()?>" class="brand-link">
        <img src="<?= $logo ?>" alt="WayPinPointLogo" class="brand-image
        img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">WayPinPoint</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center">
            <div class="image me-2">
                <img src="<?= $imgUserPath . $user->getUserExtra()->photo ?>"
                     class="img-fluid rounded-circle"
                     style="min-height: 40px; min-width: 40px">
            </div>
            <div class="info">
                <?= Html::a($user->username, ['role-register/update', 'id' => $user->id],
                    ['class' => 'd-block text-decoration-none']) ?>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <!-- href be escaped -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <?php
            if ($user->getRole() == "supplier" || $user->getRole() == "admin") {
                echo \hail812\adminlte\widgets\Menu::widget([
                    'items' => [
                        /*[
                            'label' => 'Starter Pages',
                            'icon' => 'tachometer-alt',
                            'badge' => '<span class="right badge badge-info">2</span>',
                            'items' => [
                                ['label' => 'Active Page', 'url' => ['site/index'], 'iconStyle' => 'far'],
                                ['label' => 'Inactive Page', 'iconStyle' => 'far'],
                            ]
                        ],*/

                        ['label' => 'Employee Management', 'header' => true],
                        ['label' => 'User', 'icon' => 'user', 'url' => Url::to(['role-register/index'])],
                        ['label' => 'Activities', 'header' => true],
                        ['label' => 'Bookings', 'icon' => 'file-code', 'url' => ['booking/index']],
                        ['label' => 'Manage Calendar', 'icon' => 'file-code', 'url' => ['calendar/index']],
                        ['label' => 'Create activity', 'icon' => 'plus', 'url' => ['activity/create']],
                        ['label' => 'Manage activity', 'icon' => 'map', 'url' => ['activity/index']],
                        ['label' => 'Selling Points', 'header' => true],
                        ['label' => 'My Local Shops', 'icon' => 'map', 'url' => ['localsellpoint/index']],
                        ['label' => 'Add Local Shop', 'icon' => 'plus', 'url' => ['localsellpoint/create']],
                        ['label' => 'Sales', 'header' => true],
                        ['label' => 'Manage sales', 'icon' => 'plus', 'url' => ['sale/index']],
                    ],
                ]);
            }
            if ($user->getRole() == "manager") {
                echo \hail812\adminlte\widgets\Menu::widget([
                    'items' => [
                        ['label' => 'Activities', 'header' => true],
                        ['label' => 'Bookings', 'icon' => 'file-code', 'url' => ['booking/index']],
                        ['label' => 'Manage Calendar', 'icon' => 'file-code', 'url' => ['calendar/index']],
                        ['label' => 'Manage activity', 'icon' => 'map', 'url' => ['activity/index']],
                        ['label' => 'Sales', 'header' => true],
                        ['label' => 'Manage sales', 'icon' => 'plus', 'url' => ['sale/index']],
                    ],
                ]);
            }
            if ($user->getRole() == "salesperson") {
                echo \hail812\adminlte\widgets\Menu::widget([
                    'items' => [
                        ['label' => 'Activities', 'header' => true],
                        ['label' => 'Bookings', 'icon' => 'file-code', 'url' => ['booking/index']],
                        ['label' => 'Manage activity', 'icon' => 'map', 'url' => ['activity/index']],
                        ['label' => 'Sales', 'header' => true],
                    ],
                ]);
            }
            if ($user->getRole() == "guide") {
                echo \hail812\adminlte\widgets\Menu::widget([
                    'items' => [
                        ['label' => 'Bookings', 'icon' => 'file-code', 'url' => ['booking/index']],
                    ],
                ]);
            }
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>