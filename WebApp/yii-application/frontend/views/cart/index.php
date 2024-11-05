<?php

use frontend\models\Cart;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Carts';
$this->params['breadcrumbs'][] = $this->title;
?>
    <div class="cart-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Cart', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'user_id',
        'product_id',
        'quantity',
        [
            'class' => ActionColumn::className(),
            'urlCreator' => function ($action, Cart $model, $key, $index, $column) {
                return Url::toRoute([$action, 'user_id' => $model->user_id, 'product_id' => $model->product_id]);
            }
        ],
    ],
]); ?>