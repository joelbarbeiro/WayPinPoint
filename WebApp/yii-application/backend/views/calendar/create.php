<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var \common\models\Calendar $model */

$this->title = 'Create Calendar';
?>
<div class="calendar-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
