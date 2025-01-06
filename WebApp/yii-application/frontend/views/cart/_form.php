<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var \common\models\Cart $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="cart-form">

    <?= "<strong>Name of the activity:</strong> " . $activityName ?><br>
    <?= "<strong>Date of the activity:</strong> " . $calendarDate . " " . $calendarHour ?><br>

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'quantity')->textInput(['type' => 'number', 'min' => 1]) ?>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
