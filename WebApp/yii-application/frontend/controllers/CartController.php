<?php

namespace frontend\controllers;

use frontend\models\Cart;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
/**
 * CartController implements the CRUD actions for Cart model.
 */
class CartController extends Controller
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
     * Lists all Cart models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Cart::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'user_id' => SORT_DESC,
                    'product_id' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Cart model.
     * @param int $user_id User ID
     * @param int $product_id Product ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($user_id, $product_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($user_id, $product_id),
        ]);
    }

    /**
     * Creates a new Cart model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($activityId)
    {
        $model = new Cart();
        $userId = Yii::$app->user->id;
        $model->user_id = $userId;
        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->product_id = $activityId;
                if ($model->save()) {
                    return $this->redirect(['view', 'user_id' => $model->user_id, 'product_id' => $model->product_id]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Cart model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $user_id User ID
     * @param int $product_id Product ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($user_id, $product_id)
    {
        $model = $this->findModel($user_id, $product_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'user_id' => $model->user_id, 'product_id' => $model->product_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Cart model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $user_id User ID
     * @param int $product_id Product ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($user_id, $product_id)
    {
        $this->findModel($user_id, $product_id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Cart model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $user_id User ID
     * @param int $product_id Product ID
     * @return Cart the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($user_id, $product_id)
    {
        if (($model = Cart::findOne(['user_id' => $user_id, 'product_id' => $product_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
