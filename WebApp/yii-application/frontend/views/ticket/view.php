<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var \common\models\Ticket $model */

$this->title = $model->activity->name;
$this->params['breadcrumbs'][] = ['label' => 'Tickets', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="ticket-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'data-method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'Activity Name',
                'value' => function ($model) {
                    return $model->activity->name;
                },
            ],
            [
                'label' => 'Ticket Quantity:',
                'value' => function ($model) {
                    return $model->booking->numberpax;
                },
            ],
            'participant',
            'qr',
        ],
    ]) ?>

</div>
