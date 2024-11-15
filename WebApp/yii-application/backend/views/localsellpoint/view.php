<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var backend\models\Localsellpoint $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Localsellpoints', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="localsellpoint-view">

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
                'label' => 'Owner',
                'value' => function ($model) {
                    return $model->user->username;
                }
            ],
            'address',
            'name',
            [
                'label' => 'Employees',
                'value' => function ($model) use ($employeesMap) {
                    $usernames = [];

                    foreach ($employeesMap as $user) {
                        array_push($usernames, $user);
                    }
                    return !empty($usernames) ? implode(', ', $usernames) : "No Employees";
                }
            ],
        ],
    ]) ?>

</div>