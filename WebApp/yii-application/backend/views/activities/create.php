<?php

/** @var yii\web\View $this */
/** @var \common\models\Activity $model */


$this->title = 'Create Activities';
$this->params['breadcrumbs'][] = ['label' => 'Activities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activities-create">

    <?= $this->render('_form', [
        'model' => $model,
        'hoursList' => $hoursList,
    ]) ?>

</div>
