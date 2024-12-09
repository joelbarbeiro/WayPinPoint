<?php

use common\models\Sale;
use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var common\models\Booking $model */

$this->title = 'Bookings';
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="booking-index">
    <div class="row">
        <div class="bookings w-100">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Activity</th>
                    <th>Date & Time</th>
                    <th>Nº of Tickets</th>
                    <th>Buyers</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($dataProvider->models as $booking): ?>
                    <tr>
                        <td><?= Html::encode($booking->activity->name) ?></td>
                        <td>
                            <?= Html::encode($booking->calendar->date->date) ?> -
                            <?= Html::encode($booking->calendar->time->hour) ?>
                        </td>
                        <td>
                            <?= Html::encode($booking->numberpax) ?>
                        </td>
                        <td>
                            <?= Html::encode($booking->user->username) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
