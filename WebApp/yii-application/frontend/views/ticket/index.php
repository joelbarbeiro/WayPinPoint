<?php

use common\models\Ticket;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Tickets';
?>
<div class="ticket-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'Activity',
                'value' => function ($model) {
                    return $model->activity->name;
                }
            ],
            [
                'attribute' => 'Participant',
                'value' => function ($model) {
                    return Yii::$app->user->identity->username;
                }
            ],
            [
                'class' => ActionColumn::className(),
                'template' => '{view} {print} {delete}',
                'header' => 'Actions',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a(
                            'View',
                            ['view', 'id' => $model->id],
                            [
                                'class' => 'btn btn-outline-primary',
                                'title' => 'View',
                            ]
                        );
                    },
                    'print' => function ($url, $model) {
                        return Html::a(
                            'Print Ticket',
                            ['ticket/print', 'id' => $model->id],
                            [
                                'class' => 'btn btn-outline-success',
                                'title' => 'Print Ticket',
                            ]
                        );
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(
                            'Delete',
                            ['delete', 'id' => $model->id],
                            [
                                'class' => 'btn btn-danger',
                                'title' => 'Delete',
                                'data-method' => 'post',
                                'data-confirm' => 'Are you sure you want to delete this item?',
                            ]
                        );
                    },
                ],
                'urlCreator' => function ($action, Ticket $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>
</div>
