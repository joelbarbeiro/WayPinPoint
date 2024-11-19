<?php

/** @var yii\web\View $this */
/** @var backend\models\Localsellpoint $model */

$this->title = 'Create Local Shop';
$this->params['breadcrumbs'][] = ['label' => 'Localsellpoints', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="localsellpoint-create">

    <?= $this->render('_form', [
        'model' => $model,
        'userId' => $userId,
        'employeesMap' => $employeesMap,
    ]) ?>

</div>