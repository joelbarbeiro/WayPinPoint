<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\UserExtra $model */

$this->title ="Employee : " . $model->user->username;
$this->params['breadcrumbs'][] = ['label' => 'User Extras', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-extra-view">


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
                'label' => 'Username',
                'value' => function ($model) {
                    return $model->user->username;
                }
            ],
            [
                'label' => 'Shop assigned to ',
                'value' => function ($model) {
                    return $model->localsellpoint->name;
                }
            ],
            'nif',
            'address',
            'phone',
            [
                'label' => 'Employer',
                'value' => function ($model) use ($supplier) {
                    return $supplier->username;
                }
            ],
        ],
    ]) ?>

</div>
