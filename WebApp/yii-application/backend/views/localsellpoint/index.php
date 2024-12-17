<?php

use backend\models\Localsellpoint;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\LocalsellpointSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var $model Localsellpoint */

$this->title = 'Local Shops ';

$this->registerCssFile('@web/css/site.css');
?>

<div class="localsellpoint-index">

    <p>
        <?= Html::a('Create Local Shop', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php foreach ($dataProvider->models as $local): ?>
        <div class="localsellpoint">
            <table>
                <thead>
                <tr>
                    <th>Shop Name</th>
                    <th>Address</th>
                    <th>Owner</th>
                    <th>Manager</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td><?= Html::encode($local->name) ?></td>
                    <td><?= Html::encode($local->address) ?></td>
                    <td><?= Html::encode($local->user->username) ?></td>
                    <td>
                        <?= Html::a('Edit Shop', ['localsellpoint/update', 'id' => $local->id],
                            ['class' => 'btn btn-warning']) ?>
                    </td>
                    <td>
                        <?= Html::a('Shop details', ['localsellpoint/view', 'id' => $local->id],
                            ['class' => 'btn btn-success']) ?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    <?php endforeach ?>
</div>