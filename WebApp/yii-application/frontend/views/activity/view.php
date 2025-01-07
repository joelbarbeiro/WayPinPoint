<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Activity $model */

$this->title = $model->name;
\yii\web\YiiAsset::register($this);
?>
<h1>Details for: <?=$this->title ?></h1>
<div class="activity-view">

    <?php $imgPath = Url::to('@web/img/activity/' . $model->user_id . '/'); ?>

    <div class="activities-view">

        <?php
        echo '<div class="card m-5" >';
        echo '<img src="' . $imgPath . $model->photo . '" class="card-img-top" alt="...">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . 'Activity Name: ' . $model->name . '</h5>';
        echo '<p class="card-text">' . 'Description: ' . $model->description . '</p>';
        echo '<p class="card-text">' . 'Category: ' . $model->category->description . ' People ' . '</p>';
        echo '<p class="card-text">' . 'Capacity: ' . $model->maxpax . ' People ' . '</p>';
        echo '<p class="card-text">' . 'Price per Ticket: ' . $model->priceperpax . '€' . '</p>';

        $dropdownOptions = [];
        foreach ($model->calendar as $calendar) {
            if ($calendar->status != 0) {
                $dropdownOptions[$calendar->id] = $calendar->date->date . ' - ' . $calendar->time->hour;
            }
        }
        $form = ActiveForm::begin([
            'action' => Url::to(['cart/create']),
            'method' => 'get', // Use GET to include the selected values in the URL
        ]);
        echo Html::dropDownList(
            'calendarId', // The name of the parameter
            null, // Default selected value
            $dropdownOptions, // Options for the dropdown
            ['class' => 'form-control']); // Additional HTML attributes

        echo '<div class="d-flex justify-content-between">';
        echo '<a href="' . Url::to(['review/index', 'id' => $model->id]) . '" class="btn btn-outline-warning">Review</a>';
        echo Html::hiddenInput('activityId', $model->id);
        echo Html::submitButton('Buy', [
            'class' => 'btn btn-outline-success',
        ]);
        ActiveForm::end();

        echo '</div>';
        echo '</div>';
        ?>
    </div>
</div>