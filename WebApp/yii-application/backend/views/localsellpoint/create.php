<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Localsellpoint $model */

$this->title = 'Create Localsellpoint';
$this->params['breadcrumbs'][] = ['label' => 'Localsellpoints', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="localsellpoint-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'userId' => $userId,
        'managersMap' => $managersMap,
    ]) ?>

</div>