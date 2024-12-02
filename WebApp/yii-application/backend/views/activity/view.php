<?php

use yii\helpers\Url;
use yii\web\YiiAsset;

/** @var yii\web\View $this */
/** @var common\models\Activity $model */

$this->title = "View Activity";
$this->params['breadcrumbs'][] = ['label' => 'Activity', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);

$imgPath = Url::to('@web/img/activity/' . Yii::$app->user->id . '/');

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
    echo '<table class="table">';
            echo '<tr>';
                echo '<th>Description</th>';
                echo '<td colspan="3">' . $model->description . '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<th>Max participants</th>';
                echo '<td colspan="3">' . $model->maxpax . '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<th>Price Per Pax</th>';
                echo '<td colspan="3">' . $model->priceperpax . '</td>';
            echo '</tr>';
            echo '<tr>';
                echo '<th>Location</th>';
                echo '<td colspan="3">' . $model->address . '</td>';
            echo '</tr>';
    echo '<p class="card-text">' . $model->description . '</p>';
    echo '<p class="card-text">' . $model->category->description . '</p>';

    foreach ($model->calendar as $calendar) {
        if ($calendar->status != 0) {
            echo '<tr>';
                echo '<td>Date</td>';
                echo '<td>' . $calendar->date->date . '</td>';
                echo '<td>time</td>';
                echo '<td>' . $calendar->time->hour . '</td>';
            echo '</tr>';
        }
    }
    echo '</table>';

    echo '<a href="' . Url::to(['activity/update', 'id' => $model->id]) . '" class="btn btn-warning mr-3">Update</a>';
    echo '<a href="' . Url::to(['activity/delete', 'id' => $model->id]) . '" class="btn btn-danger" data-method="post">Delete</a>';

    echo '</div>';
    echo '</div>';
    ?>
</div>
