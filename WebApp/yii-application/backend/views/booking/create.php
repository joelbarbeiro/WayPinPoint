<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Booking $model */

$this->title = 'Create Booking';
?>
<div class="booking-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
