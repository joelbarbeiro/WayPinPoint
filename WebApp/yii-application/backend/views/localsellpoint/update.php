<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Localsellpoint $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Localsellpoints', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="localsellpoint-update">

    <?= $this->render('_update', [
        'model' => $model,
        'employeesMap' => $employeesMap,
    ]) ?>

</div>