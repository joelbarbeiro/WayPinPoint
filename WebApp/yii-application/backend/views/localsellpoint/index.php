<?php

use backend\models\Localsellpoint;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\LocalsellpointSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var $model Localsellpoint */

?>
<style>
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
<div class="localsellpoint-index">

    <h1>Local Selling Points</h1>

    <p>
        <?= Html::a('Create Localsellpoint', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php foreach ($dataProvider->models as $local): ?>
        <div class="localsellpoint">
            <table>
                <thead>
                <tr>
                    <th>Shop Name</th>
                    <th>Address</th>
                    <th>Manager</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?= Html::encode($local->name) ?></td>
                    <td><small><?= Html::encode($local->address) ?></small></td>
<!--                    <td><small>--><?php //= Html::encode($local->manager) ?><!--</small></td>-->
                    <td>
                        <?= Html::a('Assign Manager', ['manager/assign', 'localId' => $local->id],
                            ['class' => 'btn btn-success']) ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    <?php endforeach ?>
</div>