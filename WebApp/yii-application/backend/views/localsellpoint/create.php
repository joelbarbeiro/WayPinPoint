<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Localsellpoint $model */

$this->title = 'Create Local Selling Point';
$this->params['breadcrumbs'][] = ['label' => 'Localsellpoints', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="localsellpoint-create">


    <?= $this->render('_form', [
        'model' => $model,
        'userId' => $userId,
        'managersMap' => $managersMap,
    ]) ?>

</div>