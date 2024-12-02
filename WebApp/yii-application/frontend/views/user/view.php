<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\YiiAsset;

/** @var yii\web\View $this */
/** @var common\models\User $model */

$this->title = "My Profile";
YiiAsset::register($this);
$this->registerCssFile('@frontend/web/css/site.css');

?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="user-view">
        <?php $imgPath = Url::to('@web/img/user/' . $model->id . '/'); ?>

        <div class="card m-5">
            <div class="row g-0 align-items-center">
                <!-- Profile Photo Section -->
                <div class="col-md-4 text-center p-3">
                    <img src="<?= $imgPath . $userExtra->photo ?>" class="img-fluid rounded-circle m-4"
                         alt="Profile Photo" style="max-height: 120px; object-fit: cover;">
                </div>

                <div class="col-md-8">
                    <div class="card-body">
                        <h5 class="card-title mb-2"><?= 'Username: ' . $model->username ?></h5>
                        <p class="card-text mb-1"><strong>Email:</strong> <?= $model->email ?></p>
                        <p class="card-text mb-1"><strong>Phone:</strong> <?= $userExtra->phone ?></p>
                        <p class="card-text mb-1"><strong>NIF:</strong> <?= $userExtra->nif ?></p>
                        <p class="card-text mb-3"><strong>Address:</strong> <?= $userExtra->address ?></p>

                        <div class="d-flex flex-wrap gap-2">
                            <a href="<?= Url::to(['user/update', 'id' => $model->id]) ?>"
                               class="btn btn-warning btn-sm">Update Profile</a>
                            <a href="<?= Url::to(['invoice/index', 'id' => $model->id]) ?>" class="btn btn-info btn-sm">My
                                Invoices</a>
                            <a href="<?= Url::to(['ticket/index', 'id' => $model->id]) ?>" class="btn btn-info btn-sm">My
                                Tickets</a>
                            <?= Html::a('Delete Profile', ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger btn-sm',
                                'data' => [
                                    'confirm' => 'Are you sure you want to delete this item?',
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>