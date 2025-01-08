<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\Localsellpoint $model */

$this->title = $model->name;
?>
<div class="localsellpoint-update">

    <?= $this->render('_update', [
        'model' => $model,
        'employeesMap' => $employeesMap,
    ]) ?>

</div>