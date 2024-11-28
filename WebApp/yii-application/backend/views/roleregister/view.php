<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var common\models\UserExtra $model */

$this->title = "Employee : " . $model->user->username;
$this->params['breadcrumbs'][] = ['label' => 'User Extras', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-extra-view">

    <?php $imgPath = Url::to('@web/assets/uploads/' . $model->user_id . '/'); ?>

    <div class="user-view">
        <div class="user-view">
            <div class="card m-5">
                <div class="row g-0 align-items-center">
                    <!-- Profile Photo Section -->
                    <div class="col-md-4 text-center p-3">
                        <img src="<?= $imgPath . $model->photo ?>" class="img-fluid rounded-circle m-4"
                             alt="Profile Photo" style="max-height: 120px; object-fit: cover;">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title mb-2"><?= 'Username: ' . $model->user->username ?></h5>
                            <p class="card-text mb-1"><strong>Email:</strong> <?= $model->user->email ?></p>
                            <p class="card-text mb-1"><strong>Phone:</strong> <?= $model->phone ?></p>
                            <p class="card-text mb-1"><strong>NIF:</strong> <?= $model->nif ?></p>
                            <p class="card-text mb-3"><strong>Address:</strong> <?= $model->address ?></p>

                            <div class="d-flex flex-wrap gap-2">
                                <a href="<?= Url::to(['role-register/update', 'id' => $model->id]) ?>"
                                   class="btn btn-warning btn-sm m-2">Update Profile</a>
                                <?= Html::a('Delete Profile', ['role-register/delete', 'id' => $model->id], [
                                    'class' => 'btn btn-danger btn-sm m-2',
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
</div>