<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var \common\models\Activities $model */
/** @var yii\widgets\ActiveForm $form */
/** @var backend\controllers\ActivitiesController $hoursList */

?>

<div class="activities-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'photoFile')->fileInput(['multiple' => false, 'accept' => 'image/*']) ?>

    <?= $form->field($model, 'maxpax')->textInput() ?>

    <?= $form->field($model, 'priceperpax')->textInput() ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <div class="date-inputs">

        foreach(
        <div class="form-group">
            <div class="date-field">
                <?= $form->field($model, 'dates[]')->input('date') ?>

                <?= $form->field($model, 'hours[]')->dropDownList($hoursList, [
                    'prompt' => 'Select Hour',
                ]); ?>
            </div>
        </div>


    </div>

    <button type="button" id="add-date-btn" class="btn btn-primary">Add another date and hour
    </button>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    document.getElementById('add-date-btn').addEventListener('click', function () {
        const newField = document.querySelector('.date-field').cloneNode(true);
        document.querySelector('.date-inputs').appendChild(newField);
    });
</script>
