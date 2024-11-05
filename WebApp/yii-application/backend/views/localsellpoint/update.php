<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Localsellpoint $model */

$this->title = 'Update Localsellpoint: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Localsellpoints', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="localsellpoint-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>