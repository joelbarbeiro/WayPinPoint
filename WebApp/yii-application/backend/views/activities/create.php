<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Activities $model */

$this->title = 'Create Activities';
$this->params['breadcrumbs'][] = ['label' => 'Activities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activities-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'hoursList' => $hoursList,
    ]) ?>

</div>
