<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var \common\models\Cart $model */

$this->title = 'Update Cart: ' . $model->user_id;
$this->params['breadcrumbs'][] = ['label' => 'Carts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user_id, 'url' => ['view', 'user_id' => $model->user_id, 'product_id' => $model->product_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="cart-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
