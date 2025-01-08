<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var \common\models\Activity $model */
/** @var backend\controllers\ActivityController $hoursList */
/** @var backend\controllers\ActivityController $categories */

$this->title = 'Update Activities: ' . $model->name;

?>
<div class="activities-update">

    <?= $this->render('_form', [
        'model' => $model,
        'categories' => $categories,
        'hoursList' => $hoursList,
    ]) ?>

</div>
