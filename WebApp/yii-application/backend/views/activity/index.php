<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\ActivitySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var yii\widgets\ActiveForm $form */


$this->title = 'Activities';
$this->params['breadcrumbs'][] = $this->title;

$imgPath = Url::to('@web/img/activity/' . Yii::$app->user->identity->userExtra->supplier . '/');

$this->registerCssFile('@web/css/site.css', [
    'depends' => [\yii\web\YiiAsset::class],
]);
?>
<div class="activities-index">
    <div class="row">
        <?php
        $counter = 0;
        foreach ($dataProvider as $activity) {
            echo '<div class="col-md-6 d-flex align-items-stretch">';
            echo '<div class="card m-3 w-100" >';
            echo '<img src="' . $imgPath . $activity->photo . '" class="card-img-top card-img-container" alt="' . $activity->name . '">';
            echo '<div class="card-body">';
            echo '<h5 class="card-title">' . $activity->name . '</h5>';
            echo '<p class="card-text">' . $activity->description . '</p>';

            echo '<p class="card-text">' . $activity->category->description . '</p>';
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
                ]
            );
            $form = ActiveForm::begin([
                'action' => ['sale/create'], // Form submission URL
                'method' => 'post',
            ]);

            echo '<div class="d-flex justify-content-between">';
            echo Html::activeHiddenInput($model, 'activity_id', ['value' => $activity->id]); // Pass activity ID
            echo Html::activeHiddenInput($model, 'calendar_id', ['value' => $calendar->id]);
            echo $form->field($model, 'buyer')->dropDownList(
                $clients,
            );
            echo $form->field($model, 'quantity')->textInput([
                'type' => 'number',
                'min' => 1,
                'value' => 1,
            ]);

            echo Html::submitButton('Buy', ['class' => 'btn btn-primary']);

            ActiveForm::end();


            if (Yii::$app->user->identity->getRole() == "supplier") {

                echo '<p class="card-text mt-3"><a href="' . Url::to(['activity/view', 'id' => $activity->id]) . '" class="btn btn-primary">View</a></p>';
            }
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