<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var frontend\models\ReviewSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Review';
$this->registerCssFile('@web/css/site.css');

?>
<div class="reviews-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Create Review', ['create', 'id' => $activityId], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="container">
        <div class="row justify-content-center g-4">
            <?php
            if (empty($activitiesReview)) {
                echo '<div class="d-flex flex-column justify-content-center align-items-center vh-50">';
                echo '<h1 class="text-center mb-4">No Reviews for this Activity</h1>';
                echo '<i class="fa fa-sad-tear fa-5x text-muted"></i>';
                echo '</div>';
            } else {
                foreach ($activitiesReview as $review) {
                    $imgPath = Url::to('@web/img/activity/' . $review->activity->user_id . '/');
                    echo '<div class="col-md-12 col-sm-12 d-flex align-items-stretch mb-12 px-3">';
                    echo '<div class="card w-100 ">';
                    echo '<img src="' . $imgPath . $review->activity->photo . '" class="card-img-top card-img-container" alt="Activity Image">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . 'Activity: ' . Html::encode($review->activity->name) . '</h5>';
                    echo '<p class="card-text">' . 'Score: ' . Html::encode($review->score) . ' out of 5.</p>';
                    echo '<p class="card-text">' . 'Comment: ' . Html::encode($review->message) . '</p>';
                    echo '<p class="card-text">' . 'Creation Date: ' . Html::encode(date('Y-m-d H:i:s', $review->created_at)) . '</p>';
                    if($review->user->id == Yii::$app->user->id){
                        echo '<a href="' . Url::to(['review/update', 'id' => $review->id]) . '" class="btn btn-warning">Edit</a>';
                    }
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </div>
</div>