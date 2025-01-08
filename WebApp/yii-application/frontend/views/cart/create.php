<?php

/** @var yii\web\View $this */
/** @var \common\models\Cart $model */
$this->title = 'Create Cart';

?>
<div class="cart-create">

    <?= $this->render('_form', [
        'model' => $model,
        'calendarId' => $calendarId,
        'activityName' => $activityName,
        'calendarDate' => $calendarDate,
        'calendarHour' => $calendarHour,
    ]) ?>

</div>
