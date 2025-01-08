<?php
/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var \common\models\LoginForm $model */

use yii\bootstrap5\Html;
use yii\helpers\Url;

$logo = Url::to('@web/img/logo/waypinpoint.png');

?>
<div class="d-flex flex-column justify-content-center align-items-center vh-50 p-3">
    <img src="<?= $logo?>" alt="Logo Waypinpoint" class="rounded-5" >
</div>
<div class="card rounded-5">
    <div class="card-body login-card-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <?php $form = \yii\bootstrap4\ActiveForm::begin(['id' => 'login-form']) ?>

        <?= $form->field($model,'username', [
            'options' => ['class' => 'form-group has-feedback'],
            'template' => '{beginWrapper}{input}{error}{endWrapper}',
            'wrapperOptions' => ['class' => 'input-group mb-3']
        ])
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>

        <?= $form->field($model, 'password', [
            'options' => ['class' => 'form-group has-feedback'],
            'template' => '{beginWrapper}{input}{error}{endWrapper}',
            'wrapperOptions' => ['class' => 'input-group mb-3']
        ])
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

        <div class="row">
            <div class="col-8">
                <?= $form->field($model, 'rememberMe')->checkbox([
                    'template' => '<div class="icheck-primary">{input}{label}</div>',
                    'labelOptions' => [
                        'class' => ''
                    ],
                    'uncheck' => null
                ]) ?>
            </div>
            <div class="d-flex flex-column justify-content-center align-items-center vh-50 p-3">
                <?= Html::submitButton('Sign In', ['class' => 'btn btn-outline-primary btn-block']) ?>
            </div>
        </div>

        <?php \yii\bootstrap4\ActiveForm::end(); ?>

        <p class="mb-0">
            <a href="<?= \yii\helpers\Url::toRoute('site/register')?>">Register new supplier</a>
        </p>
    </div>
    <!-- /.login-card-body -->
</div>