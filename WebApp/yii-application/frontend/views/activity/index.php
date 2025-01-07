<?php

use common\models\Activity;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var \common\models\ActivitySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var Activity $model title */

$this->title = 'Activities';
$this->registerCssFile('@web/css/site.css');
?>
<div class="activity-index">
    <div class="container">
        <div class="row">
            <?php
            $counter = 0;
            if (empty($dataProvider->models)) {
                echo '<div class="d-flex flex-column justify-content-center align-items-center vh-50">';
                echo '<h1 class="text-center mb-4">No Activities Found or Available</h1>';
                echo '<i class="fa fa-sad-tear fa-5x text-muted"></i>';
                echo '</div>';
            } else {
                echo '<h1>' . Html::encode($this->title) . '</h1>';
                foreach ($dataProvider->models as $activity) {

                    $imgPath = Url::to('@web/img/activity/' . $activity->user_id . '/');

                    echo '<div class="col-md-6 d-flex align-items-stretch mb-4">';
                    echo '<div class="card w-100">';
                    echo '<img src="' . $imgPath . $activity->photo . '" class="card-img-top card-img-container" alt="Activity Image">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">Activity Name: ' . Html::encode($activity->name) . '</h5>';
                    echo '<p class="card-text">Description: ' . Html::encode($activity->description) . '</p>';
                    echo '<p class="card-text">Category: ' . Html::encode($activity->category->description) . '</p>';
                    echo '<p class="card-text">Price per Ticket: ' . Html::encode($activity->priceperpax . "€") . '</p>';

                    $dropdownOptions = [];
                    foreach ($activity->calendar as $calendar) {
                        if ($calendar->status != 0) {
                            $dropdownOptions[$calendar->id] = $calendar->date->date . ' - ' . $calendar->time->hour;
                        }
                    }
                    $form = ActiveForm::begin([
                        'action' => Url::to(['cart/create']),
                        'method' => 'get',
                    ]);
                    echo Html::dropDownList(
                        'calendarId',
                        null,
                        $dropdownOptions,
                        ['class' => 'form-control']);

                    echo '<div class="d-flex justify-content-between">';
                    echo '<a href="' . Url::to(['activity/view', 'id' => $activity->id]) . '" class="btn btn-primary">View</a>';
                    echo '<a href="' . Url::to(['review/index', 'id' => $activity->id]) . '" class="btn btn-outline-warning">Review</a>';

                    echo Html::hiddenInput('activityId', $activity->id);
                    echo Html::submitButton('Buy', [
                        'class' => 'btn btn-outline-success',
                    ]);
                    ActiveForm::end();
//                    echo '<a href="' . Url::to(['cart/create', 'activityId' => $activity->id, 'calendarId' => $calendar->id]) . '" class="btn btn-outline-success">Buy</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                    $counter++;
                    if ($counter % 2 == 0) {
                        echo '</div><div class="row">';
                    }
                }
            }
            ?>
        </div>
    </div>
</div>