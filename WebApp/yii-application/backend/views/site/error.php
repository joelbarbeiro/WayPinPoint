<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
$userId = Yii::$app->user->id;
$frontend = '../../frontend/web/';
$this->title = $name;
$this->params['breadcrumbs'] = [['label' => $this->title]];
?>
<div class="error-page">
    <div class="error-content" style="margin-left: auto;">
        <h3><i class="fas fa-exclamation-triangle text-danger"></i> <?= Html::encode($name) ?></h3>

        <p>
            <?= nl2br(Html::encode($message)) ?>
        </p>

        <p>
            The above error occurred while the Web server was processing your request.
            Please contact us if you think this is a server error. Thank you.
        </p>
        <?= Html::a('Return to dashboard', ['site/logout'], [
            'class' => 'btn btn-primary',
            'data' => [
                'method' => 'post',
            ],
        ]) ?>
        <form class="search-form" style="margin-right: 190px;">
            <div class="input-group">
                <label>
                    <input type="text" name="search" class="form-control" placeholder="Search">
                </label>
                <div class="input-group-append">
                    <button type="submit" name="submit" class="btn btn-danger"><i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

