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
                    [
                        'label' => 'Starter Pages',
                        'icon' => 'tachometer-alt',
                        'badge' => '<span class="right badge badge-info">2</span>',
                        'items' => [
                            ['label' => 'Active Page', 'url' => ['site/index'], 'iconStyle' => 'far'],
                            ['label' => 'Inactive Page', 'iconStyle' => 'far'],
                        ]
                    ],
                    ['label' => 'Employee Management', 'header' => true],
                    ['label' => 'User', 'icon' => 'user', 'url' => Url::to(['role-register/role-register'])],
                    //['label' => 'Simple Link', 'icon' => 'th', 'badge' => '<span class="right
                    // badge badge-danger">New</span>'],
                    ['label' => 'Activities', 'header' => true],
                    //['label' => 'Bookings', 'url' => ['site/login'], 'icon' => 'sign-in-alt',
                    // 'visible' => Yii::$app->user->isGuest],
                    ['label' => 'Bookings', 'icon' => 'file-code', 'url' => ['/gii'], 'target' =>
                        '_blank'],
                    ['label' => 'Create activity', 'icon' => '', 'url' => ['activities/create'], 'iconStyle' => 'far',],
                    ['label' => 'Manage activity', 'icon' => 'map', 'url' => ['activities/index'], 'iconStyle' => 'far',],
                    ['label' => 'Selling Points', 'header' => true],
                    ['label' => 'My Selling Points', 'icon' => 'map', 'url' => Url::to(['localsellpoint/index'])],
                    ['label' => 'Add Selling Point', 'icon' => 'plus', 'url' => Url::to(['localsellpoint/create'])],
                    ['label' => 'Sales', 'header' => true],
                ],
            ]);
            ?>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>