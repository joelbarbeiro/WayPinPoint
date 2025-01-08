<?php

use backend\models\backendregister;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ActivitySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Activities';
?>
    <div class="signup-form">
        <h1><?= Html::encode($this->title) ?></h1>

        <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'email')->input('email') ?>
        <?= $form->field($model, 'password')->passwordInput() ?>
        <?= $form->field($model, 'role')->dropDownList([
            'admin' => 'Admin',
            'editor' => 'Editor',
            'user' => 'User',
        ], ['prompt' => 'Select Role']) ?>

        <div class="form-group">
            <?= Html::submitButton('Register User', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>






