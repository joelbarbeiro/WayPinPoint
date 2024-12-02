<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Activity $model */
/** @var yii\widgets\ActiveForm $form */
/** @var backend\controllers\ActivityController $hoursList */

?>

<div class="activities-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= $form->field($model, 'photoFile')->fileInput(['multiple' => false, 'accept' => 'image/*', 'id' => 'photoFile']) ?>

        <!-- Small window to preview the image -->
        <div id="photoPreview" style="margin-top: 10px;">
            <?php if ($model->photo): ?>
                <img src="<?= Yii::getAlias('@web/assets/uploads/' . $model->user_id . '/' . $model->photo) ?>"
                     alt="Current Image"
                     style="max-width: 150px; max-height: 150px; display: block;">
            <?php else: ?>
                <p>No image uploaded yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <?= $form->field($model, 'maxpax')->textInput() ?>

    <?= $form->field($model, 'priceperpax')->textInput() ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <div class="date-inputs row row-cols-1 row-cols-md-4">
        <?php
        if (!$model->calendar == null) {
            foreach ($model->calendar as $date) {
                if($date->status == 1) {
                    echo '<div class="col">';
                    echo '<div class="form-group">';
                    echo '<div class="date-field">';
                    echo $form->field($model, 'date[]')->input('date', ['value' => $date->date->date]);

                    echo $form->field($model, 'hour[]')->dropDownList($hoursList, [
                        'prompt' => 'Select Hour',
                        'value' => $date->time,
                    ]);
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            }
        } else { ?>
            <div class="col">
                <div class="form-group">
                    <div class="date-field">
                        <?= $form->field($model, 'date[]')->input('date') ?>

                        <?= $form->field($model, 'hour[]')->dropDownList($hoursList, [
                            'prompt' => 'Select Hour',
                        ]); ?>

                    </div>
                </div>
            </div>
        <?php } ?>

    </div>

    <button type="button" id="add-date-btn" class="btn btn-primary mt-2 mb-2">Add another date and hour
    </button>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success m-2']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    document.getElementById('add-date-btn').addEventListener('click', function () {
        const newField = document.querySelector('.date-field').cloneNode(true);
        newField.querySelector('input[type="date"]').value = '';
        newField.querySelector('select').selectedIndex = 0;
        document.querySelector('.date-inputs').appendChild(newField);
    });
    document.querySelector('.date-inputs').addEventListener('click', function (event) {
        if (event.target.classList.contains('remove-date-btn')) {
            const dateField = event.target.closest('.form-group');
            if (dateField) {
                dateField.remove();
            }
        }
    });
    document.getElementById('photoFile').addEventListener('change', function (event) {
        const preview = document.getElementById('photoPreview');
        const file = event.target.files[0];

        preview.innerHTML = '';

        if (file) {
            const reader = new FileReader();
            reader.onload = function (e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '150px';
                img.style.maxHeight = '150px';
                img.style.display = 'block';
                preview.appendChild(img);
            };
            reader.readAsDataURL(file);
        } else {
            preview.innerHTML = '<p>No image selected.</p>';
        }
    });
</script>
