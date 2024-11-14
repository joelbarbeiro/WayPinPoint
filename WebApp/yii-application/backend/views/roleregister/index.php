<?php

use common\models\UserExtra;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\UserExtraSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'User Extras';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .btn-danger, .btn-warning {
        width: 100%
    }

    table {
        width: 100%;
    }

    th, td {
        width: 33%;
        text-align: center;
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }
</style>

<div class="user-extra-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create User Extra', ['role-register'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php foreach ($dataProvider->models as $employee): ?>
    <?php if($employee->user->status == 0) continue; ?>
        <div class="employees">
            <table>
                <thead>
                <tr>
                    <th>Username</th>
                    <th>NIF</th>
                    <th>Address</th>
                    <th>Phone</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?= Html::encode($employee->user->username) ?></td>
                    <td><?= Html::encode($employee->nif) ?></td>
                    <td><?= Html::encode($employee->address) ?></td>
                    <td><?= Html::encode($employee->phone) ?></td>
                    <td>
                        <?= Html::a('Edit Employee', ['role-register/update', 'id' => $employee->id],
                            ['class' => 'btn btn-warning']) ?>
                    </td>
                    <td>
                        <?= Html::a('Soft Delete', ['role-register/delete', 'id' => $employee->user->id],
                            ['class' => 'btn btn-danger']) ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    <?php endforeach ?>

<!--    --><?php //= GridView::widget([
//        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
//        'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
//            [
//                'label' => 'Username',
//                'value' => function ($model) {
//                    return $model->user->username;
//                }
//            ],
//            'nif',
//            'address',
//            'phone',
//            [
//                'class' => ActionColumn::className(),
//                'urlCreator' => function ($action, UserExtra $model, $key, $index, $column) {
//                    return Url::toRoute([$action, 'id' => $model->user_id]);
//                 }
//            ],
//        ],
//    ]); ?>


</div>
