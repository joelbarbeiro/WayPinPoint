<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var frontend\models\Review $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="reviews-form">

    <?php $form = ActiveForm::begin(); ?>

    <h3>Score</h3>
    <label>
        <input type="radio" name="Review[score]" value="1"> ★
    </label>
    <label>
        <input type="radio" name="Review[score]" value="2"> ★★
    </label>
    <label>
        <input type="radio" name="Review[score]" value="3"> ★★★
    </label>
    <label>
        <input type="radio" name="Review[score]" value="4"> ★★★★
    </label>
    <label>
        <input type="radio" name="Review[score]" value="5"> ★★★★★
    </label>

    <?= $form->field($model, 'message')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>