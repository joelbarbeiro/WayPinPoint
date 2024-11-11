<?php

namespace backend\controllers;

use backend\models\Localsellpoint;
use backend\models\RoleRegisterForm;
use common\models\UserExtra;
use common\models\UserExtraSearch;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class RoleRegisterController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $searchModel = new UserExtraSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $userId = Yii::$app->user->id;
        $employees = UserExtra::find()
            ->select(['id'])
            ->where(['supplier' => $userId])
            ->asArray()
            ->all();
        $employeesMap = ArrayHelper::map($employees, 'id', 'username');
        return $this->render('@backend/views/roleregister/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'employeesMap' => $employeesMap,
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
        $localsellpoints = Localsellpoint::find()
            ->select(['id', 'name'])
            ->asArray()
            ->all();
        $localsellpointsMap = ArrayHelper::map($localsellpoints, 'id', 'name');

        if ($model->load($this->request->post()) && $model->roleRegister()) {
            \Yii::$app->session->setFlash('success', 'User registered successfully with roleregister: ' . $model->role);
            return $this->redirect(['site/index']); // Adjust to where you want to redirect
        }
        return $this->render('@backend/views/roleregister/roleregister', [
            'userExtra' => $userExtra,
            'localsellpoints' => $localsellpointsMap,
            'model' => $model
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->user->delete();
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = UserExtra::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['role-register', 'index', 'delete'],
                        'allow' => true,
                        'roles' => ['supplier'], // Only logged-in users can access this action
                    ],
                ],
            ],
        ];
    }
}