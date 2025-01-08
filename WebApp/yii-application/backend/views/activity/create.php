<?php

/** @var yii\web\View $this */
/** @var \common\models\Activity $model */
/** @var backend\controllers\ActivityController $hoursList */
/** @var backend\controllers\ActivityController $categories */

$this->title = 'Create Activities';
?>
<div class="activities-create">

    <?= $this->render('_form', [
        'model' => $model,
        'categories' => $categories,
        'hoursList' => $hoursList,
    ]); ?>

</div>
