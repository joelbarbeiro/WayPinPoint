<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var frontend\models\Review $model */

$this->title = 'Create Review';

?>
<div class="reviews-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>