<?php

/** @var yii\web\View $this */
/** @var \common\models\Activities $model */

public $hoursList = [];

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
