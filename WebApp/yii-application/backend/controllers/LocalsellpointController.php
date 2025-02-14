<?php

namespace backend\controllers;

use backend\models\Localsellpoint;
use backend\models\LocalsellpointSearch;
use common\models\UserExtra;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * LocalsellpointController implements the CRUD actions for Localsellpoint model.
 */
class LocalsellpointController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            ['access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'register'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'view'],
                        'allow' => false,
                        'roles' => ['client'],
                    ],
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'view'],
                        'allow' => true,
                        'roles' => ['admin', 'supplier'],
                    ],
                    [
                        'actions' => ['index','update', 'view'],
                        'allow' => true,
                        'roles' => ['manager'],
                    ],
                    [
                        'actions' => ['view'],
                        'allow' => true,
                        'roles' => ['guide', 'salesperson'],
                    ],
                    [
                        'actions' => ['logout', 'index'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],

                ],
            ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Localsellpoint models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new LocalsellpointSearch();
        $userId = Yii::$app->user->id;
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->query
            ->andWhere(['user_id' => $userId]);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Localsellpoint model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $userId = Yii::$app->user->id;
        $authManager = Yii::$app->authManager;

        $users = Localsellpoint::getEmployeesForLocalStore($id, $userId);

        $manager = [];

        $employees = [];

        foreach ($users as $user) {
            $roles = $authManager->getRolesByUser($user['id']); // Fetch roles for the user
            if (!isset($roles['manager']) && !isset($roles['supplier'])) { // Only add users who are NOT managers or Suppliers
                $employees[] = $user['username'];
            }
        }

        foreach ($users as $user) {
            $roles = $authManager->getRolesByUser($user['id']); // Fetch roles for the user
            if (isset($roles['manager'])) { // Check if 'manager' exists in roles
                $manager[] = $user['username'];
            }
        }

        return $this->render('view', [
            'model' => $this->findModel($id),
            'users' => $users,
            'manager' => $manager,
            'employees' => $employees,
        ]);
    }

    /**
     * Creates a new Localsellpoint model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */

    public function actionCreate()
    {
        $model = new Localsellpoint();
        $userId = Yii::$app->user->id;

        $managerUserNames = UserExtra::getManagersForSupplier($userId);

        $employeesMap = ArrayHelper::map($managerUserNames, 'id', 'username');

        $model->user_id = $userId;
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'userId' => $userId,
            'employeesMap' => $employeesMap,
        ]);
    }

    /**
     * Updates an existing Localsellpoint model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = Localsellpoint::findOne($id);
        $userId = Yii::$app->user->id;

        $userExtras = UserExtra::getEmployeesForSupplier($userId);

        $employeesMap = ArrayHelper::map($userExtras, 'id', 'username');

        $model->user_id = $userId;

        if ($this->request->isPost && $model->load($this->request->post())) {

            $selectedEmployeeIds = $model->assignedEmployees;
            $usersToAssign = UserExtra::find()->where(['id' => $selectedEmployeeIds])->all();

            if (!empty($usersToAssign)) {

                foreach ($usersToAssign as $user) {
                    $user->localsellpoint_id = $model->id;
                    $user->save();
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'employeesMap' => $employeesMap,
        ]);
    }

    /**
     * Deletes an existing Localsellpoint model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $localShop = $this->findModel($id);
        $localShop->status = 0;
        $localShop->save();
        return $this->redirect(['index']);
    }

    /**
     * Finds the Localsellpoint model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Localsellpoint the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Localsellpoint::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}