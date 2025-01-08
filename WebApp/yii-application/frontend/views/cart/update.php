<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var \common\models\Cart $model */

$this->title = 'Update Cart: ' . $model->user_id;

?>
<div class="cart-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
