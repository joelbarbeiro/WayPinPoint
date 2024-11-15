<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Calendar $model */

$this->title = "View Activitie";
$this->params['breadcrumbs'][] = ['label' => 'Activities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);

$imgPath = Url::to('@web/assets/uploads/'.Yii::$app->user->id.'/');

?>
<div class="activities-view">

    <?php
    echo '<div class="card m-5" >';
    echo '<img src="' . $imgPath . $model->photo . '" class="card-img-top" alt="...">';
    echo '<div class="card-body">';
    echo '<h5 class="card-title">' . $model->name . '</h5>';
    echo '<p class="card-text">' . $model->description . '</p>';

    foreach ($model->calendars as $calendar) {
        if ($calendar->status != 0) {
            echo '<p class="card-text"> Date: ' . $calendar->date->date . ' Time: ' . $calendar->time->hour . '</p>';
        }
    }

    echo '<a href="' . Url::to(['activities/update', 'id' => $model->id]) . '" class="btn btn-primary mr-3">Edit</a>';
    echo '<a href="' . Url::to(['activities/delete', 'id' => $model->id]) . '" class="btn btn-danger" data-method="post">Delete</a>';

    echo '</div>';
    echo '</div>';
    ?>
</div>
