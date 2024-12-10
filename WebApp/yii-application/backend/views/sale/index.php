<?php

use common\models\Calendar;
use common\models\Sale;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var Sale $model title */

$this->title = 'Sales';
$this->params['breadcrumbs'][] = $this->title;
$imgPath = Url::to('@web/assets/uploads/' . Yii::$app->user->id . '/');
$this->registerCssFile('@web/css/site.css');

?>
<div class="sale-index">
    <div class="row">
        <div class="sales w-100">
            <table>
                <thead>
                <tr>
                    <th>Activity</th>
                    <th>Client</th>
                    <th>Sell point</th>
                    <th>Seller</th>
                    <th>Purchase Date</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                <?php
                foreach ($dataProvider as $sale):
                    if(Yii::$app->user->identity->getRole() == "supplier")
                        {
                    ?>
                <tr>
                    <td><?= Html::encode($sale->activity->name) ?></td>
                    <td><?= Html::encode($sale->buyer0->username) ?></td>
                    <td><?= Html::encode($sale->sellerExtra->localsellpoint->name ?? "website") ?></td>
                    <td><?= Html::encode($sale->seller->username) ?></td>
                    <td><?= Html::encode($sale->purchase_date) ?></td>
                    <td><?= Html::encode($sale->quantity) ?></td>
                    <td><?= Html::encode($sale->total) ?></td>
                </tr>
                <?php }
                if(Yii::$app->user->identity->getRole() == "manager")
                {
                    if (Yii::$app->user->identity->userExtra->localsellpoint_id == $sale->localsellpoint_id) { ?>
                    <tr>
                        <td><?= Html::encode($sale->activity->name) ?></td>
                        <td><?= Html::encode($sale->buyer0->username) ?></td>
                        <td><?= Html::encode($sale->sellerExtra->localsellpoint->name) ?></td>
                        <td><?= Html::encode($sale->seller->username) ?></td>
                        <td><?= Html::encode($sale->purchase_date) ?></td>
                        <td><?= Html::encode($sale->quantity) ?></td>
                        <td><?= Html::encode($sale->total) ?></td>
                    </tr>
                <?php }
                } ?>
                <?php endforeach;?>
                </tbody>
            </table>
        </div>
    </div>
</div>

