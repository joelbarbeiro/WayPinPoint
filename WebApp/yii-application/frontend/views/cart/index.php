<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
$this->title = 'Shopping Cart';
$this->params['breadcrumbs'][] = $this->title;
?>
    <h1><?= Html::encode($this->title) ?></h1>
    <div class="cart-index">

        <table class="table">
            <thead>
            <tr>
                <th scope="col">User</th>
                <th scope="col">Description</th>
                <th scope="col">Quantity</th>
                <th scope="col">Price</th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            <?php

            foreach ($dataProvider as $cart) {
                echo '<tr>';
                echo '<td>' . $cart->user->username . '</td>';
                echo '<td>' . $cart->activity->description . '</td>';
                echo '<td>' . $cart->quantity . '</td>';
                echo '<td>' . $cart->activity->priceperpax . '</td>';
                echo '<td><a href="' . Url::to(['cart/checkout', 'id' => $cart->id]) . '" class="btn btn-primary">Checkout</a></td>';
                echo '<td><a href="' . Url::to(['cart/delete', 'user_id' => $cart->user_id, 'product_id' => $cart->product_id]) . '" class="btn btn-danger" data-method="post" data-confirm="Are you sure you want to delete this item?">Delete</a></td>';
                echo '</tr>';
            }

            ?>

            </tbody>
        </table>
    </div>

<?php //= GridView::widget([
//    'dataProvider' => $dataProvider,
//    'columns' => [
//        ['class' => 'yii\grid\SerialColumn'],
//        ['attribute' => 'User',
//            'value' => function ($model) {
//                return $model->user->username;
//            }
//        ],
//        ['attribute' => 'Description',
//            'value' => function ($model) {
//                return $model->activity->description;
//            }],
//        ['attribute' => 'Quantity',
//            'value' => function ($model) {
//                return $model->quantity;
//            }],
//        ['attribute' => 'Price',
//            'value' => function ($model) {
//                return $model->activity->priceperpax . "€";
//            }],
//        [
//            'class' => ActionColumn::className(),
//            'template' => ' {delete} {checkout}',
//            'buttons' => [
//                'checkout' => function ($url, $model, $key) {
//                    return Html::beginForm(['cart/checkout'], 'post')
//                        . Html::hiddenInput('id', $model->id)
//                        . Html::submitButton('Checkout', ['class' => 'btn btn-primary',
//                            'data-confirm' => 'Are you sure you want to purchase this activity?',
//                            'data-method' => 'post'])
//                        . Html::endForm();
//
//                }],
//            'urlCreator' => function ($action, Cart $model, $key, $index, $column) {
//                return Url::toRoute([$action, 'user_id' => $model->user_id, 'product_id' => $model->product_id]);
//            }
//        ],
//
//    ],
//]); ?>