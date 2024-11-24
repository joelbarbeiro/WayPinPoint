<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var \common\models\Activity $model */
/** @var backend\controllers\ActivityController $hoursList */

$this->title = 'Update Activities: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Activity', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

?>
<div class="activities-update">

    <?= $this->render('_form', [
        'model' => $model,
        'hoursList' => $hoursList,
    ]) ?>

</div>
