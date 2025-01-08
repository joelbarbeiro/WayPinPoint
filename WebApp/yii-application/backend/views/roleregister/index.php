<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\UserExtraSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Employees ';
$this->registerCssFile('@web/css/site.css');

?>

<div class="user-extra-index">

    <p>
        <?= Html::a('Register an Employee', ['role-register'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php foreach ($dataProvider->models as $employee): ?>
        <?php
        if ($employee->user->status == 0 ||
            $employee->supplier != Yii::$app->user->id ||
            $employee->user->getRole() == 'supplier') continue;
        ?>
        <?php $imgPath = Url::to('@web/img/user/' . $employee->user_id . '/'); ?>

        <div class="user-view">

            <div class="card m-5">
                <div class="row g-0 align-items-center">
                    <!-- Profile Photo Section -->
                    <div class="col-md-4 text-center p-3">
                        <img src="<?= $imgPath . $employee->photo ?>" class="img-fluid rounded-circle m-4"
                             alt="Profile Photo" style="max-height: 120px; object-fit: cover;">
                    </div>

                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title mb-2"><?= 'Username: ' . $employee->user->username ?></h5>
                            <p class="card-text mb-1"><strong>Email:</strong> <?= $employee->user->email ?></p>
                            <p class="card-text mb-1"><strong>Phone:</strong> <?= $employee->phone ?></p>
                            <p class="card-text mb-1"><strong>NIF:</strong> <?= $employee->nif ?></p>
                            <p class="card-text mb-3"><strong>Address:</strong> <?= $employee->address ?></p>

                            <div class="d-flex flex-wrap gap-2">
                                <?= Html::a('Edit Employee', ['role-register/update', 'id' => $employee->id],
                                    ['class' => 'btn btn-warning btn-sm m-2']) ?>
                                <?= Html::a('Remove Employee', ['role-register/delete', 'id' => $employee->user->id],
                                    ['class' => 'btn btn-danger btn-sm m-2',
                                        'data' => [
                                            'confirm' => 'Are you sure you want to remove this employee?',
                                            'method' => 'post',
                                        ],
                                    ]) ?>
                                <?= Html::a('Change Password', ['role-register/update-password', 'id' => $employee->id],
                                    ['class' => 'btn btn-info btn-sm m-2']) ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>
