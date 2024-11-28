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
    <?= $form->field($model, 'photoFile')->fileInput(['multiple' => false, 'accept' => 'image/*']) ?>
    <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
    <?= $form->field($model, 'email')->input('email') ?>
    <?= $form->field($model, 'phone')->textInput() ?>
    <?= $form->field($model, 'address')->textInput() ?>
    <?= $form->field($model, 'nif')->input('number') ?>
    <?= $form->field($model, 'localsellpoint')->dropDownList(
        $localsellpointsMap,
        ['prompt' => 'Select a Local Shop for employee to be assigned']
    ); ?>
    <?= $form->field($model, 'role')->dropDownList([
        'manager' => 'manager',
        'salesperson' => 'salesperson',
        'guide' => 'guide',
    ], ['prompt' => 'Select Role']) ?>
    <div class="form-group">
        <?= Html::submitButton('Save Changes', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
    </div>
    <?php ActiveForm::end() ?>
</div>