<?php

use common\models\Activity;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var \common\models\ActivitySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var Activity $model title */

$this->title = 'Activities';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="activity-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
    foreach ($dataProvider->models as $activity) {
        $imgPath = Url::to('@web/assets/uploads/' . $activity->user_id . '/');

        echo '<div class="card m-5" >';

        echo '<img src="' . $imgPath . $activity->photo . '" class="card-img-top" alt="...">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . $activity->name . '</h5>';
        echo '<p class="card-text">' . $activity->description . '</p>';
        echo '<p class="card-text">'. $activity->priceperpax .'€' .'</p>';

        foreach ($activity->calendars as $calendar) {
            if ($calendar->status != 0) {
                echo '<p class="card-text"> Date: ' . $calendar->date->date . ' Time: ' . $calendar->time->hour . '</p>';
            }
        }

        echo '<div class="d-flex justify-content-between p-3">';
        echo '<a href="' . Url::to(['activity/view', 'id' => $activity->id]) . '" class="btn btn-primary">View</a>';
        echo '<a href="' . Url::to(['review/index' ,'id' => $activity->id]) . '" class="btn btn-outline-warning">Reviews</a>';
        echo '<a href="' . Url::to(['cart/create' ,'activityId' => $activity->id]) . '" class="btn btn-outline-success">Buy</a>';

        echo '</div>';

        echo '</div>';
        echo '</div>';
    }
    ?>
</div>
