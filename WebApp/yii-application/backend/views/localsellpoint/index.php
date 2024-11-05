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
                    <td><?= Html::encode($local->address) ?></td>
                    <td><?= Html::encode($local->manager->username) ?></td>
                    <td><small>
                        <?= Html::a('Assign Manager', ['localsellpoint/update', 'id' => $local->id],
                            ['class' => 'btn btn-success']) ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    <?php endforeach ?>
</div>