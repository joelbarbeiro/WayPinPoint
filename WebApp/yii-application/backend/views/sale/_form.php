<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Sale $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="sale-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'activity_id')->dropDownList(
        $activities,
        ['prompt' => 'Select Activity']
    ); ?>

    <?= $form->field($model, 'quantity')->textInput([
        'type' => 'number',
        'min' => 1,
        'value' => 1,
    ]) ?>

    <?= $form->field($model, 'total')->hiddenInput(['readonly' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
