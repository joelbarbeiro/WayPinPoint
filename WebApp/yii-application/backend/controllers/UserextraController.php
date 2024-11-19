<?php

namespace backend\controllers;

use backend\models\Localsellpoint;
use backend\models\RoleRegisterForm;
use common\models\User;
use common\models\UserExtra;
use common\models\UserExtraSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * UserextraController implements the CRUD actions for UserExtra model.
 */
class UserextraController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
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
     * Lists all UserExtra models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserExtraSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UserExtra model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new UserExtra model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new UserExtra();
        $user = new User();
        $localsellpoints = Localsellpoint::find()
            ->select(['id', 'name'])
            ->asArray()
            ->all();

        if ($model->load($this->request->post()) && $model->roleRegister()) {
            \Yii::$app->session->setFlash('success', 'User registered successfully with roleregister: ' . $model->role);
            return $this->redirect(['site/index']); // Adjust to where you want to redirect
        }
        else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'user' => $user,
            'localsellpoints' => $localsellpoints,
        ]);
    }

    /**
     * Updates an existing UserExtra model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing UserExtra model.
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
     * Finds the UserExtra model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return UserExtra the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UserExtra::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
