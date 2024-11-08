<?php

use frontend\models\Cart;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Shopping Cart';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="cart-index">

    <h1><?= Html::encode($this->title) ?></h1>


<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
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
        ['attribute' => 'Price',
            'value' => function ($model){
                return $model->activity->priceperpax . "€";
            }],
        [
            'class' => ActionColumn::className(),
            'template' => '{view} {delete} {update} {myButton}',
            'buttons' => ['myButton' => function ($url, $model, $key) {
                    return Html::beginForm(['cart/download'], 'post')
                        . Html::hiddenInput('user_id', $model->user_id)
                        . Html::hiddenInput('activity_id', $model->product_id)
                        . Html::submitButton('Checkout', ['class' => 'btn btn-primary' ,
                            'data-confirm' => 'Are you sure you want to purchase this activity?',
                            'data-method' => 'post'])
                        . Html::endForm();
            }],
            'urlCreator' => function ($action, Cart $model, $key, $index, $column) {
                return Url::toRoute([$action, 'user_id' => $model->user_id, 'product_id' => $model->product_id]);
            }
        ],
    ],
]); ?>