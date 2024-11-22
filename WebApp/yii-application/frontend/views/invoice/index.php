<?php

use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;

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
                'template' => '{print}',
                'buttons' => [
                    'print' => function ($url, $model) {
                        return Html::a(
                            'Print Invoice',
                            ['invoice/print', 'id' => $model->id], // Pass the invoice ID
                            [
                                'class' => 'btn btn-primary',
                                'title' => 'Print Invoice',
                            ]
                        );
                    }
                ]
            ]
        ],
    ]); ?>
</div>
