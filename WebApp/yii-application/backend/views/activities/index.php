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

?>
<div class="activities-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php
        foreach ($dataProvider->models as $activity) {

            echo '<div class="card" ">';
            echo '<img src="backend/upload/'.$activity->photo.'" class="card-img-top" alt="...">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">'.$activity->name.'</h5>';
            echo '<p class="card-text">'.$activity->description.'</p>';

            foreach ($activity->calendars as $calendar) {
                echo '<p class="card-text"> Date: ' . $calendar->date->date . ' Time: ' . $calendar->time->hour . '</p>';
            }

            echo '<a href="#" class="btn btn-primary">Edit</a>';
            echo '</div>';
            echo '</div>';
        }
    ?>
</div>
