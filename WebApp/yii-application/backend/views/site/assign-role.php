<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\User $user */
/** @var yii\rbac\Role[] $roles */
/** @var string|null $currentRole */

$this->title = 'Assign Role to ' . $user->username;
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="role-assignment-form">
    <?php $form = ActiveForm::begin(); ?>

    <p><strong>Current Role:</strong> <?= $currentRole ?: 'None' ?></p>

    <?= $form->field($user, 'username')->textInput(['readonly' => true]) ?>

    <?= $form->field($user, 'role')->dropDownList(
        array_map(function($role) {
            return $role->name;
        }, $roles),
        ['prompt' => 'Select a role']
    )->label('Assign New Role') ?>

    <div class="form-group">
        <?= Html::submitButton('Assign Role', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
