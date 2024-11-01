<?php

use common\models\User;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var User $user */
/** @var yii\rbac\Role[] $roles */
/** @var string|null $currentRole */
/** @var User[] $users */ // Add this line

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

    <h2>All Users</h2>
    <ul>
        <?php foreach ($users as $user): ?>
            <li>
                <?= Html::encode($user->username) ?> (<?= Html::encode($user->email) ?>)
                <?= Html::a('Assign Role', ['site/roleassign', 'id' => $user->id], ['class' => 'btn btn-info']) ?>
            </li>
        <?php endforeach; ?>
    </ul>

    <div class="form-group">
        <?= Html::submitButton('Assign Role', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
<br>
<h1><?= Html::encode($this->title) ?></h1>

<table class="table table-bordered">
    <thead>
    <tr>
        <th>Username</th>
        <th>Email</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($users as $user): ?>
        <tr>
            <td><?= Html::encode($user->id) ?></td>
            <td><?= Html::encode($user->username) ?></td>
            <td><?= Html::encode($user->email) ?></td>
            <td>
                <?= Html::a('Assign Role', ['user/assign-role', 'id' => $user->id], ['class' => 'btn btn-info']) ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
