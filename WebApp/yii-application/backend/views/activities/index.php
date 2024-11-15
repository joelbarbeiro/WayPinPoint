<?php

use backend\models\Activities;
use backend\models\Calendar;
use backend\models\Times;
use backend\models\Dates;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\ActivitiesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Activities';
$this->params['breadcrumbs'][] = $this->title;

$imgPath = Url::to('@web/assets/uploads/'.Yii::$app->user->id.'/');

?>
<div class="activities-index">

    <?php
        foreach ($dataProvider->models as $activity) {

            echo '<div class="card m-5" >';
            echo '<img src="'.$imgPath.$activity->photo.'" class="card-img-top" alt="...">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">'.$activity->name.'</h5>';
            echo '<p class="card-text">'.$activity->description.'</p>';

            foreach ($activity->calendars as $calendar) {
                if($calendar->status != 0) {
                    echo '<p class="card-text"> Date: ' . $calendar->date->date . ' Time: ' . $calendar->time->hour . '</p>';
                }
            }

            echo '<a href="' . Url::to(['activities/view', 'id' => $activity->id]) . '" class="btn btn-primary">View</a>';
            echo '</div>';
            echo '</div>';
        }
    ?>
</div>
