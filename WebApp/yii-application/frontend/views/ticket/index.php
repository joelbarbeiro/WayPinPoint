<?php

use common\models\Ticket;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Tickets';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="ticket-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            ['attribute' => 'Activity',
                'value' => function ($model) {
                    return $model->activity->description;
                }],
            ['attribute' => 'Participant',
                'value' => function ($model) {
                    return Yii::$app->user->identity->username;
                }],
            ['attribute' => 'Status',
                'value' => function ($model) {
                    return $model->status;
                }],
            [
                'class' => ActionColumn::className(),
                'template' => '{view},{delete}, {print}',
                'buttons' => [
                    'print' => function ($url, $model) {
                        return Html::a(
                            'Print Ticket',
                            ['ticket/print', 'id' => $model->id],
                            [
                                'class' => 'btn btn-primary',
                                'title' => 'Print Ticket',
                            ]);
                    }
                ],
                'urlCreator' => function ($action, Ticket $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                }
            ],
        ],
    ]); ?>


</div>
