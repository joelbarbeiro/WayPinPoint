<?php

use common\models\Invoice;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var \common\models\Invoice $model */

$this->title = 'Invoices';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="invoice-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'username',
                'label' => 'User',
                'value' => function ($model) {
                    //dd($model);
                    return Yii::$app->user->identity->username;
                }
            ],
            ['attribute' => 'Purchase Date',
                'value' => function ($model) {
                    return $model->sale->purchase_date;
                }
            ],
            ['attribute' => 'Total',
                'value' => function ($model) {
                    return $model->sale->total . "€";
                }
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Invoice $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
