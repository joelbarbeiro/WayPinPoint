<?php

/** @var yii\web\View $this */
/** @var \common\models\Cart $model */
$this->title = 'Create Cart';
$this->params['breadcrumbs'][] = ['label' => 'Carts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cart-create">


    <?= $this->render('_form', [
        'model' => $model,
        'calendarId' => $calendarId,
    ]) ?>

</div>
