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

        $employees = UserExtra::getEmployeesForSupplier($userId);


        $employeesMap = ArrayHelper::map($employees, 'id', 'username');
        return $this->render('@backend/views/roleregister/index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'employeesMap' => $employeesMap,
        ]);
    }

    public function actionRoleRegister()
    {
        $userId = Yii::$app->user->id;
        $model = new RoleRegisterForm();
        $userExtra = new UserExtra();

        $localsellpoints = Localsellpoint::getLocalStoreForSupplier($userId);

        $localsellpointsMap = ArrayHelper::map($localsellpoints, 'id', 'name');

        if ($model->load($this->request->post()) && $model->roleRegister()) {
            \Yii::$app->session->setFlash('success', 'User registered successfully with role: ' . $model->role);
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

        $model->photo = $userExtra->photo;
        $model->username = $user->username;
        $model->email = $user->email;
        $model->phone = $userExtra->phone;
        $model->address = $userExtra->address;
        $model->nif = $userExtra->nif;
        $model->localsellpoint = $userExtra->localsellpoint_id;
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

        return $this->render('@backend/views/roleregister/update', [
            'userExtra' => $userExtra,
            'localsellpointsMap' => $localsellpointsMap,
            'model' => $model
        ]);
    }

    public function actionUpdatePassword($id)
    {
        $model = new RoleRegisterForm();
        $userExtra = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->roleUpdatePassword($id)) {
                \Yii::$app->session->setFlash('success', 'User updated successfully with role: ' . $model->role);
                return $this->redirect(['view', 'id' => $id]);
            } else {
                \Yii::$app->session->setFlash('error', 'Failed to update user');
            }
        }

        return $this->render('@backend/views/roleregister/_password', [
            'userExtra' => $userExtra,
            'model' => $model
        ]);
    }

    public function actionDelete($id)
    {
        $user = User::findOne($id);
        $userextra = UserExtra::findOne(['user_id' => $user->id]);
        $user->status = 0;
        $userextra->status = 0;
        $userextra->photo = null;
        if ($userextra->validate()) {
            if ($userextra->save() && $user->save()) {
                return $this->redirect(['role-register/index']);
            } else {
                dd($user->getErrors(), $userextra->getErrors());
            }
        } else {
            dd($user->getErrors(), $userextra->getErrors());
        }
    }

    protected
    function findModel($id)
    {
        if (($model = UserExtra::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public
    function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['role-register', 'index', 'delete', 'view', 'update', 'role-update', 'update-password'],
                        'allow' => true,
                        'roles' => ['supplier'],
                    ],
                ],
            ],
        ];
    }
}