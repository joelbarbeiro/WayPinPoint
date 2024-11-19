<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var \common\models\Cart $model */

$this->title = $model->activity->description;
$this->params['breadcrumbs'][] = ['label' => 'Carts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="cart-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'user_id' => $model->user_id, 'product_id' => $model->product_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'user_id' => $model->user_id, 'product_id' => $model->product_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            ['attribute' => 'User',
                'value' => function ($model){
                    return $model->user->username;
                }
            ],
            ['attribute' => 'Description',
                'value' => function ($model){
                    return $model->activity->description;
                }],
            ['attribute' => 'Quantity',
                'value' => function ($model){
                    return $model->quantity;
                }],
        ],
    ]) ?>

</div>
