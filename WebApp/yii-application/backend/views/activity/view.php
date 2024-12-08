<?php

use yii\helpers\Url;
use yii\web\YiiAsset;

/** @var yii\web\View $this */
/** @var common\models\Activity $model */

$this->title = "View Activity";
$this->params['breadcrumbs'][] = ['label' => 'Activity', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);

$imgPath = Url::to('@web/img/activity/' . $userId = Yii::$app->user->identity->userExtra->supplier . '/');

$this->registerCssFile('@web/css/site.css', [
    'depends' => [\yii\web\YiiAsset::class],
]);
?>
<div class="activities-view">

    <?php
    echo '<div class="card m-5" >';
    echo '<img src="' . $imgPath . $model->photo . '" class="card-img-top card-img-container" alt="' . $model->name . '">';
    echo '<div class="card-body">';
    echo '<h5 class="card-title">' . $model->name . '</h5>';
    echo '<p class="card-text">Description: ' . $model->description . '</p>';
    echo '<p class="card-text">Category: ' . $model->category->description . ' People ' . '</p>';
    echo '<p class="card-text">Capacity: ' . $model->maxpax . ' People ' . '</p>';
    echo '<p class="card-text">Price per Ticket: ' . $model->priceperpax . '€' . '</p>';

    foreach ($model->calendar as $calendar) {
        if ($calendar->status != 0) {
            echo '<p class="card-text">Date: ' . $calendar->date->date . ' Time: ' . $calendar->time->hour . '</p>';
        }
    }
    if (Yii::$app->user->getRole() == "supplier") {
        echo '<a href="' . Url::to(['activity/update', 'id' => $model->id]) . '" class="btn btn-warning mr-3">Update</a>';
        echo '<a href="' . Url::to(['activity/delete', 'id' => $model->id]) . '" class="btn btn-danger" data-method="post">Delete</a>';
    }
    echo '</div>';
    echo '</div>';
    ?>
</div>
