<?php

use backend\models\Activities;
use backend\models\Calendar;
use backend\models\Times;
use backend\models\Dates;

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\ActivitiesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Activities';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activities-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Activities', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'otherDataProvider' => $otherDataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'description',
            'photo',
            'maxpax',
            'priceperpax',
            'address',
            [
                'label' => 'Date',
                'value' =>  $otherDataProvider ? $otherDataProvider->date->date : 'No date',
            ],
            [
                'label' => 'Time',
                'value' => $otherDataProvider ? $otherDataProvider->time->hour : 'No time',
            ],
            [
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Activities $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
