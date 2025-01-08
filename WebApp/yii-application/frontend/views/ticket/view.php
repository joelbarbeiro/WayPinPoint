<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var \common\models\Ticket $model */

$this->title = $model->activity->name;

\yii\web\YiiAsset::register($this);
?>
<div class="ticket-view">

    <h1><?= Html::encode($this->title) ?></h1>


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
