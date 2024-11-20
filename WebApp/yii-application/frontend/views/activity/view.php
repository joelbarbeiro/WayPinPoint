<?php

use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var frontend\models\Activity $model */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Activities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="activity-view">

    <?php $imgPath = Url::to('@web/assets/uploads/' . $model->user_id . '/'); ?>

    <div class="activities-view">

        <?php
        echo '<div class="card m-5" >';
        echo '<img src="' . $imgPath . $model->photo . '" class="card-img-top" alt="...">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . $model->name . '</h5>';
        echo '<p class="card-text">' . $model->description . '</p>';

        foreach ($model->calendar as $calendar) {
            if ($calendar->status != 0) {
                echo '<p class="card-text"> Date: ' . $calendar->date->date . ' Time: ' . $calendar->time->hour . '</p>';
            }
        }

        echo '<a href="' . Url::to(['activity/update', 'id' => $model->id]) . '" class="btn btn-primary mr-3">Edit</a>';
        echo '<a href="' . Url::to(['activity/delete', 'id' => $model->id]) . '" class="btn btn-danger" data-method="post">Delete</a>';

        echo '</div>';
        echo '</div>';
        ?>
    </div>

</div>