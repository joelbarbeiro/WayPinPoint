<?php

namespace backend\controllers;

use backend\models\RegisterForm;
use Yii;
use yii\widgets\ActiveForm;


class RegisterController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new RegisterForm();

        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            Yii::$app->session->setFlash('success', 'Registration successful. You can now log in.');
            return $this->redirect(['site/login']);
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    public function actionTest()
    {
        $form = new ActiveForm();
        return $this->renderContent("Bootstrap 5 ActiveForm loaded successfully");
    }

}
