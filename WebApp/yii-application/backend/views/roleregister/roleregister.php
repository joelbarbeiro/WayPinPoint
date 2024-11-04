<?php

/** @var yii\web\View $this * */
/** @var yii\bootstrap5\ActiveForm $form * */

/** @var RoleRegisterForm $model * */

use backend\models\RoleRegisterForm;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

?>

<div class="signup-form">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
    <?= $form->field($model, 'email')->input('email') ?>
    <?= $form->field($model, 'password')->passwordInput() ?>
    <?= $form->field($model, 'role')->dropDownList([
        'manager' => 'manager',
        'salesperson' => 'salesperson',
        'guide' => 'guide',
    ], ['prompt' => 'Select Role']) ?>

    <div class="form-group">
        <?= Html::submitButton('Register User', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
    </div>
    <?php ActiveForm::end()?>
</div>