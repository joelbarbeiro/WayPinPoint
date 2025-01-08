<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Sale $model */

$this->title = $model->activity->name;
\yii\web\YiiAsset::register($this);
?>
<div class="sale-view">


    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
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
            [
                'attribute' => 'activity_id',
                'value' => $model->activity ? $model->activity->name : 'N/A', // Assuming `name` is the field in `Activity`
            ],
            [
                'attribute' => 'buyer',
                'value' => $model->buyer0 ? $model->buyer0->username : 'N/A', // Assuming `username` is the field in `User`
            ],
            [
                'attribute' => 'total',
                'format' => ['currency', 'EUR']
            ],
            [
                'attribute' => 'purchase_date',
                'value' => $model->purchase_date,
            ],
            [
                'attribute' => 'seller_id',
                'value' => $model->seller ? $model->seller->username : 'N/A', // Assuming `username` is the field in `User`
            ],
        ],
    ]) ?>

</div>
