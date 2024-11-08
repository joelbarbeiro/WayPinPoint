<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\Localsellpoint $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="localsellpoint-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'manager_id')->dropDownList(
        $managersMap,
        ['prompt' => 'Select a Manager']
    ); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>