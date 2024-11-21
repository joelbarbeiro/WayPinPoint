<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\UserExtraSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Employees ';
$this->params['breadcrumbs'][] = $this->title;
$this->registerCssFile('@web/css/site.css');

?>

<div class="user-extra-index">


    <p>
        <?= Html::a('Register an Employee', ['role-register'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php foreach ($dataProvider->models as $employee): ?>
        <?php
        if ($employee->user->status == 0 ||
            $employee->supplier != Yii::$app->user->id ||
            $employee->user->getRole() == 'supplier') continue;
        ?>
        <div class="employees">
            <table>
                <thead>
                <tr>
                    <th>Username</th>
                    <th>NIF</th>
                    <th>Address</th>
                    <th>Phone</th>
                    <th>Position</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?= Html::encode($employee->user->username) ?></td>
                    <td><?= Html::encode($employee->nif) ?></td>
                    <td><?= Html::encode($employee->address) ?></td>
                    <td><?= Html::encode($employee->phone) ?></td>
                    <td><?= Html::encode($employee->user->getRole()) ?></td>
                    <td>
                        <?= Html::a('Edit Employee', ['role-register/update', 'id' => $employee->id],
                            ['class' => 'btn btn-warning']) ?>
                    </td>
                    <td>
                        <?= Html::a('Remove Employee', ['role-register/delete', 'id' => $employee->user->id],
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
