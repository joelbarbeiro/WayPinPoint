<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\UserExtra $model */

$this->title = 'Update Employee: ' . $model->user->username;
$this->params['breadcrumbs'][] = ['label' => 'User Extras', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-extra-update">

    <?= $this->render('_form', [
        'localsellpointsMap' => $localsellpointsMap,
        'model' => $model,
    ]) ?>

</div>
