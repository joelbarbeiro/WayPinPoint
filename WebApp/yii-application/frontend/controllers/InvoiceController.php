<?php

namespace frontend\controllers;

use common\models\Activity;
use common\models\Calendar;
use common\models\Invoice;
use common\models\InvoiceSearch;
use common\models\Sale;
use common\models\User;
use Mpdf\Mpdf;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * InvoiceController implements the CRUD actions for Invoice model.
 */
class InvoiceController extends Controller
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
     * Lists all Invoice models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new InvoiceSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $dataProvider->query->joinWith('sale')
            ->andWhere(['user' => Yii::$app->user->id]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Invoice model.
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
     * Creates a new Invoice model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Invoice();
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
     * Updates an existing Invoice model.
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
     * Deletes an existing Invoice model.
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
     * Finds the Invoice model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Invoice the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Invoice::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionPrint($id)
    {
        $invoice = Invoice::findOne($id);
        if (!$invoice) {
            Yii::$app->session->setFlash('error', "Invoice not found");
            return $this->redirect(['index']);
        }
        $saleId = $invoice->sale_id;
        $sale = Sale::findOne($saleId);
        $activity = Activity::findOne($sale->activity_id);
        if (!$saleId) {
            Yii::$app->session->setFlash('error', "Sale not found");
            return $this->redirect(['index']);
        }
        if (!$activity) {
            Yii::$app->session->setFlash('error', 'Activity not found for this sale.');
            return $this->redirect(['index']);
        }
        $userId = $invoice->user;
        $user = User::findOne($userId);
        if (!$user) {
            Yii::$app->session->setFlash('error', 'Activity not found for this sale.');
            return $this->redirect(['index']);
        }
        $calendar = Calendar::find()->where(['activity_id' => $activity->id])->all();
        $content = $this->renderPartial('printinvoice', [
            'user' => $user,
            'activity' => $activity,
            'calendar' => $calendar,
            'sale' => $sale,
        ]);
        $this->generatePdf($content, $user, $activity);
    }
    public function generatePdf($content, $user, $activity)
    {
        $pdf = new Mpdf();
        $pdf->WriteHTML($content);
        return Yii::$app->response->sendContentAsFile($pdf->Output('', 'S'), "receipt_{$user->username}_{$activity->description}.pdf", [
            'mimeType' => 'application/pdf',
        ]);
    }

}