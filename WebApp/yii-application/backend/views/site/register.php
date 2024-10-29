<?php
/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var backend\models\RegisterForm $model */

use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Register';
?>
<h1></h1>
<div class="register-box">
    <div class="register-logo">
        <a href=""><b>Backend</b> Registration</a>
    </div>

    <div class="card">
        <div class="card-body register-card-body">
            <p class="login-box-msg">Register a new membership</p>

            <?php $form = ActiveForm::begin(['id' => 'register-form']); ?>

            <?= $form->field($model, 'username')->textInput(['placeholder' => 'Username'])->label(false) ?>
            <?= $form->field($model, 'email')->textInput(['placeholder' => 'Email'])->label(false) ?>
            <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password'])->label(false) ?>

            <div class="row">
                <div class="col-12">
                    <?= Html::submitButton('Register', ['class' => 'btn btn-primary btn-block']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
    </div>
</div>
