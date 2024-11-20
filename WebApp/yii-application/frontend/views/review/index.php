<?php

use yii\helpers\Html;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var frontend\models\ReviewSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Review';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reviews-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?= Html::a('Create Review', ['create', 'id' => $activityId], ['class' => 'btn btn-success']) ?>
    </p>

    <?php
    foreach ($dataProvider->models as $review) {
        $imgPath = Url::to('@web/assets/uploads/' . $review->activity->user_id . '/');
        echo '<div class="card m-5" >';

        echo '<img src="' . $imgPath . $review->activity->photo . '" class="card-img-top" alt="...">';
        echo '<div class="card-body">';
        echo '<h5 class="card-title">' . 'Activity : ' . $review->activity->name . '</h5>';
        echo '<p class="card-text">' . 'Score : ' . $review->score . ' out of 5.</p>';
        echo '<p class="card-text">' . 'Comment : ' . $review->message . '</p>';
        echo '<p class="card-text">' . 'Creation Date : ' . $review->created_at . '</p>';
        echo '</div>';
    }
    ?>

</div>