<?php

namespace backend\controllers;

use backend\models\Localsellpoint;
use backend\models\RoleRegisterForm;
use common\models\User;
use common\models\UserExtra;
use common\models\UserExtraSearch;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class RoleRegisterController extends \yii\web\Controller
{
    /**
     * @var mixed
     */

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

    public function actionView($id)
    {
        $supplier = Yii::$app->user->identity;
        return $this->render('@backend/views/roleregister/view', [
            'model' => $this->findModel($id),
            'supplier' => $supplier
        ]);
    }

    public function actionUpdate($id)
    {
        $model = new RoleRegisterForm();
        $userExtra = $this->findModel($id);
        $user = $userExtra->user;

        $model->username = $user->username;
        $model->email = $user->email;
        $model->phone = $user->userExtra->phone ?? '';
        $model->address = $user->userExtra->address ?? '';
        $model->nif = $user->userExtra->nif ?? '';
        $model->localsellpoint = $user->userExtra->localsellpoint_id ?? '';
        $model->role = $user->getRole();

        $localsellpoints = Localsellpoint::find()
            ->select(['id', 'name'])
            ->asArray()
            ->all();
        $localsellpointsMap = ArrayHelper::map($localsellpoints, 'id', 'name');

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->roleUpdate($id)) {
                \Yii::$app->session->setFlash('success', 'User updated successfully with role: ' . $model->role);
                return $this->redirect(['view', 'id' => $id]);
            } else {
                \Yii::$app->session->setFlash('error', 'Failed to update user');
            }
        }

        return $this->render('@backend/views/roleregister/roleregister', [
            'userExtra' => $model,
            'localsellpoints' => $localsellpointsMap,
            'model' => $model
        ]);
    }

    public function actionDelete($id)
    {
        $user = User::findOne($id);
        $user->status = 0;
        $user->save();
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
                        'actions' => ['role-register', 'index', 'delete', 'view', 'update'],
                        'allow' => true,
                        'roles' => ['supplier'], // Only logged-in users can access this action
                    ],
                ],
            ],
        ];
    }
}