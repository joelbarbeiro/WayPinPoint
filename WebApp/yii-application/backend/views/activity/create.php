<?php

/** @var yii\web\View $this */
/** @var \common\models\Activity $model */
/** @var backend\controllers\ActivityController $hoursList */

$this->title = 'Create Activities';
$this->params['breadcrumbs'][] = ['label' => 'Activity', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activities-create">

    <?= $this->render('_form', [
        'model' => $model,
        'categories' => $categories,
        'hoursList' => $hoursList,
    ]); ?>

</div>
