<?php

namespace backend\controllers;

use backend\models\RoleRegisterForm;
use common\models\UserExtra;
use Yii;
use yii\filters\AccessControl;

class RoleRegisterController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new RoleRegisterForm();

        if ($model->load($this->request->post()) && $model->roleRegister()) {
            Yii::$app->session->setFlash('success', 'Registration successful. You can now log in.');
            return $this->redirect(['site/login']);
        }

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    public function actionTest()
    {
        $form = new RoleRegisterForm();
        return $this->renderContent("Bootstrap 5 ActiveForm loaded successfully");
    }

    public function actionRoleRegister()
    {
        $model = new RoleRegisterForm();
        $userExtra = new UserExtra();
        if ($model->load($this->request->post()) && $model->roleRegister()) {
            \Yii::$app->session->setFlash('success', 'User registered successfully with roleregister: ' . $model->role);
            return $this->redirect(['site/index']); // Adjust to where you want to redirect
        }
        return $this->render('@backend/views/roleregister/roleregister', [
            'userExtra' => $userExtra,
            'model' => $model
        ]);
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['role-register'],
                        'allow' => true,
                        'roles' => ['@'], // Only logged-in users can access this action
                    ],
                ],
            ],
        ];
    }
}