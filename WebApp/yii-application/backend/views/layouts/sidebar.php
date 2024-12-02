<?php

use yii\helpers\Url;

/** @var yii\backend\assets $assetDir */

$logo = Url::to('@web/assets/logo/waypinpoint.png');
?>

<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="<?= $logo ?>" alt="WayPinPointLogo" class="brand-image
        img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">WayPinPoint</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="<?= $assetDir ?>/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block"><?= $user->username ?></a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <!-- href be escaped -->
        <!-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> -->

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <?php
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
                    //['label' => 'Bookings', 'url' => ['site/login'], 'icon' => 'sign-in-alt',
                    // 'visible' => Yii::$app->user->isGuest],
                    ['label' => 'Bookings', 'icon' => 'file-code', 'url' => ['booking/index']],
                    ['label' => 'Manage Calendar', 'icon' => 'file-code', 'url' => ['calendar/index']],
                    ['label' => 'Create activity', 'icon' => 'plus', 'url' => ['activity/create']],
                    ['label' => 'Manage activity', 'icon' => 'map', 'url' => ['activity/index']],
                    ['label' => 'Selling Points', 'header' => true],
                    ['label' => 'My Local Shops', 'icon' => 'map', 'url' =>['localsellpoint/index']],
                    ['label' => 'Add Local Shop', 'icon' => 'plus', 'url' => ['localsellpoint/create']],
                    ['label' => 'Sales', 'header' => true],
                    ['label' => 'Manage sales', 'icon' => 'plus', 'url' => ['sale/index']],
                    ['label' => 'All Sales', 'icon' => 'map', 'url' => ['sale/allsales']],
                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>