<?php

use common\models\Activity;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var \common\models\ActivitySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var Activity $model title */

$this->title = 'Activities';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="activity-index">

    <h1><?= Html::encode($this->title) ?></h1>


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'name',
            'description',
            'photo',
            'maxpax',
            ['attribute' => 'priceperpax',
                'label' => 'Price',
                'value' => function ($model) {
                    return $model->priceperpax . "€";
                }],
            'address',
            [   
                'class' => ActionColumn::className(),
                'template' => '{view} {myButton}',
                'buttons' => ['myButton' => function ($url, $model) {
                    return Html::a('Buy', ['cart/create', 'activityId' => $model->id], ['class' => 'btn btn-primary']);
                }],
                'urlCreator' => function ($action, \common\models\Activity $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>
</div>
