<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var frontend\models\Review $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="reviews-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'score')->radioList([
        1 => '1 Star',
        2 => '2 Stars',
        3 => '3 Stars',
        4 => '4 Stars',
        5 => '5 Stars',
    ], [
        'itemOptions' => [
            'class' => 'radio-inline', // Optional: Add custom class for styling
        ],
    ]) ?>

    <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>