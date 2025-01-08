<?php

namespace backend\models;

use Yii;
use yii\web\ErrorAction;

class CustomErrorAction extends ErrorAction
{
    public function run()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            if ($exception->statusCode === 403) {
               $this->view = 'loginerror';
            }else{
                $this->view = 'error';
            }
        }
        return parent::run();
    }
}