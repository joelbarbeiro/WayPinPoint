<?php

/** @var yii\web\View $this */
/** @var common\models\UserExtra $model */

?>
<div class="user-extra-update">

    <?= $this->render('_form', [
        'localsellpointsMap' => $localsellpointsMap,
        'userRole' => $userRole,
        'userExtra' => $userExtra,
        'model' => $model,
    ]) ?>

</div>
