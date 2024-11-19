<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var frontend\models\ActivitySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Activities';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="activity-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <!--    --><?php // echo $this->render('_search', ['model' => $searchModel]); ?>


    <?php
    foreach ($dataProvider->models as $activity) {
        $imgPath = Url::to('@web/assets/uploads/' . $activity->user_id . '/');

        echo '<div class="card m-5" >';

        echo '<img src="' . $imgPath . $activity->photo . '" class="card-img-top" alt="...">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . $activity->name . '</h5>';
        echo '<p class="card-text">' . $activity->description . '</p>';

        foreach ($activity->calendars as $calendar) {
            if ($calendar->status != 0) {
                echo '<p class="card-text"> Date: ' . $calendar->date->date . ' Time: ' . $calendar->time->hour . '</p>';
            }
        }

        echo '<a href="' . Url::to(['activity/view', 'id' => $activity->id]) . '" class="btn btn-primary">View</a>';
        echo '</div>';
        echo '</div>';
    }
    ?>

</div>
