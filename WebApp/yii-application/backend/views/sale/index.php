<?php

use common\models\Calendar;
use common\models\Sale;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var Sale $model title */

$this->title = 'Sales';
$this->params['breadcrumbs'][] = $this->title;
$imgPath = Url::to('@web/assets/uploads/' . Yii::$app->user->id . '/');
$calendar = new Calendar();
?>
<div class="sale-index">

    <p>
        <?= Html::a('Register new Sale', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="activities-index">
        <div class="row">
            <?php
            $counter = 0;
            foreach ($dataProvider->models as $activity) {
                echo '<div class="col-md-6 d-flex align-items-stretch">';
                echo '<div class="card m-3 w-100" >';
                echo '<img src="' . $imgPath . $activity->photo . '" class="card-img-top card-img-container" alt="' . $activity->name . '">';
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $activity->name . '</h5>';
                echo '<p class="card-text">' . $activity->description . '</p>';

                $dropdownOptions = [];
                foreach ($activity->calendar as $calendar) {
                    if ($calendar->status != 0) {
                        //echo '<p class="card-text"> Date: ' . $calendar->date->date . ' Time: ' . $calendar->time->hour . '</p>';
                        $dropdownOptions[$calendar->id] = $calendar->date->date . ' - ' . $calendar->time->hour;
                    }
                }
                echo Html::dropDownList(
                    'calendar',
                    null,
                    $dropdownOptions,
                    [
                        'class' => 'card-link',
                        'prompt' => 'Select a date',
                    ]
                );


                echo '<p class="card-text mt-3"><a href="' . Url::to(['activity/view', 'id' => $activity->id]) . '" class="btn btn-primary">View</a></p>';
                echo '<p class="card-text mt-3"><a href="' . Url::to(['sale/create', 'id' => $activity->id, 'calendar_id' => $calendar->id]) . '" class="btn btn-primary">Buy</a></p>';
//                foreach ($activity->calendar as $calendar) {
//                    if ($calendar->status != 0) {
//                        echo '<p class="card-text mt-3"><a href="' . Url::to(['sale/create', 'id' => $activity->id, 'calendar_id' => $calendar->id]) . '" class="btn btn-primary">Buy for ' . $calendar->date->date . ' - ' . $calendar->time->hour . '</a></p>';
//                    }
//                }

                echo '</div>';
                echo '</div>';
                echo '</div>';

                $counter++;
                if ($counter % 2 == 0) {
                    echo '</div><div class="row">';
                }
            }
            ?>
        </div>
    </div>


</div>
