<?php

namespace frontend\controllers;

use common\models\User;
use common\models\UserExtra;
use common\models\UserSearch;
use frontend\models\SignupForm;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
     * Lists all User models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView()
    {
        $id = Yii::$app->user->id;
        $userExtra = UserExtra::findOne(['user_id' => $id]);

        return $this->render('view', [
            'model' => $this->findModel($id),
            'userExtra' => $userExtra
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new User();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ForbiddenHttpException
     */
    public function actionUpdate($id)
    {
        $currentUserId = Yii::$app->user->id;

        $model = new SignupForm();
        $userExtra = UserExtra::findOne(['user_id' => $id]);
        $user = $userExtra->user;

        $model->username = $user->username;
        $model->photo = $userExtra->photo;
        $model->email = $user->email;
        $model->phone = $userExtra->phone;
        $model->address = $userExtra->address;
        $model->nif = $userExtra->nif;

        // Prevent users from updating other users' profiles
        if ($id != $currentUserId) {
            throw new ForbiddenHttpException('You are not allowed to access this page.');
        }

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->updateUser($id)) {
                return $this->redirect(['view', 'id' => $id]);
            } else {
                \Yii::$app->session->setFlash('error', 'Failed to update user');
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionDelete($id)
    {
        $userExtra = UserExtra::findOne(['user_id' => $id]);
        $userExtra->delete();
        $this->findModel($id)->delete();
        return $this->redirect(['activity/index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}