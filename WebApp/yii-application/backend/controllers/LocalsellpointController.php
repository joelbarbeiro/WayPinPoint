<?php

namespace backend\controllers;

use backend\models\Localsellpoint;
use backend\models\LocalsellpointSearch;
use common\models\User;
use Yii;
use yii\db\Query;
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
                        'actions' => ['index', 'create', 'update', 'delete', 'view'], // Backoffice actions
                        'allow' => false,
                        'roles' => ['client'], // Explicitly deny client access to backoffice
                    ],
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'view'],
                        'allow' => true,
                        'roles' => ['admin', 'supplier', 'manager', 'salesperson', 'guide'],
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
        $localStore = Localsellpoint::findOne($id);

        $userId = Yii::$app->user->id;
        $users = $localStore->user::find()
            ->select(['username'])
            ->andWhere(['id' => (new Query())
                ->select('user_id')
                ->from('userextras')
                ->where(['supplier' => $userId])
            ])
            ->asArray()
            ->all();

        $employeesMap = ArrayHelper::map($users, 'id', 'username');

        return $this->render('view', [
            'model' => $this->findModel($id),
            'employeesMap' => $employeesMap,
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
        $managerIds = Yii::$app->authManager->getUserIdsByRole('manager');
        $managerUserNames = User::find()
            ->select(['id', 'username'])
            ->where(['id' => $managerIds])
            ->andWhere(['id' => (new Query())
                ->select('user_id')
                ->from('userextras')
                ->where(['supplier' => $userId])
            ])
            ->asArray()
            ->all();

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
        $users = User::find()
            ->select(['username'])
            ->andWhere(['id' => (new Query())
                ->select('user_id')
                ->from('userextras')
                ->where(['supplier' => $userId])
            ])
            ->asArray()
            ->all();

        $employeesMap = ArrayHelper::map($users, 'id', 'username');
        $model->user_id = $userId;

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
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
        $this->findModel($id)->delete();

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