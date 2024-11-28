<?php

/** @var yii\web\View $this * */
/** @var yii\bootstrap5\ActiveForm $form * */

/** @var RoleRegisterForm $model * */

use backend\models\RoleRegisterForm;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

?>

<div class="signup-form">

    <?php $form = ActiveForm::begin(['id' => 'form-update']); ?>

    <?= $form->field($model, 'password')->passwordInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save Changes', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
    </div>
    <?php ActiveForm::end() ?>
</div>