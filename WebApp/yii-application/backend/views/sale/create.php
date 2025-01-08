<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Sale $model */

$this->title = 'Create Sale';
?>
<div class="sale-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'activities' => $activities,
        'calendar_id' => $calendar_id,
        'clients' => $clients,
    ]) ?>

</div>
