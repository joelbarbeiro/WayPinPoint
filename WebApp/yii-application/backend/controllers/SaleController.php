<?php

namespace backend\controllers;

use backend\models\SaleSearch;
use common\models\Activity;
use common\models\Sale;
use common\models\UserExtra;
use Yii;
use yii\db\Expression;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * SaleController implements the CRUD actions for Sale model.
 */
class SaleController extends Controller
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
     * Lists all Sale models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new SaleSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $userId = Yii::$app->user->identity->userextra->supplier;

        $dataProvider = Sale::getSupplierSales($userId);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Sale model.
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
     * Creates a new Sale model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($calendar_id)
    {
        $model = new Sale();
        $userId = Yii::$app->user->id;
        $seller = UserExtra::findOne(['user_id' => $userId]);
        $activities = Activity::getSupplierActivityNames($seller->supplier);
        $clients = Sale::getAllClients();
        $clientsMap = ArrayHelper::map($clients, 'id', 'username');
        $model->seller_id = $seller->id;
        $model->localsellpoint_id = $seller->localsellpoint_id;
        if (!$seller || !$seller->supplier) {
            throw new NotFoundHttpException('Supplier information is missing.');
        }
        if ($this->request->isPost) {
            $model->load($this->request->post());
            if ($model->buyer === null) {
                throw new BadRequestHttpException('Buyer information is missing.');
            }
            if ($calendar_id === null) {
                throw new BadRequestHttpException('Calendar ID is required.');
            }
            $activity = Activity::findOne($this->request->post('Sale')['activity_id']);
            if (!$activity) {
                throw new NotFoundHttpException('Activity not found.');
            }
            $model->purchase_date = new Expression('NOW()');
            $model->total = $activity->priceperpax * $model->quantity;
            if ($model->save()) {
                Sale::createBooking($activity, $model->buyer, $calendar_id, $model->quantity);
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                var_dump($model->getErrors());
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'activities' => $activities,
            'calendar_id' => $calendar_id,
            'clients' => $clientsMap,
        ]);
    }

    /**
     * Updates an existing Sale model.
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
     * Deletes an existing Sale model.
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
     * Finds the Sale model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Sale the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sale::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
