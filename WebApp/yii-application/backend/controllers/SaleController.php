<?php

namespace backend\controllers;

use backend\models\SaleSearch;
use common\models\Activity;
use common\models\Booking;
use common\models\Cart;
use common\models\Invoice;
use common\models\Sale;
use common\models\Ticket;
use common\models\UserExtra;
use Da\QrCode\QrCode;
use Mpdf\Mpdf;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
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
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['index', 'create', 'update', 'delete', 'view'],
                            'allow' => false,
                            'roles' => ['client', 'guide'],
                        ],
                        [
                            'actions' => ['index', 'create', 'update', 'delete', 'view', 'print'],
                            'allow' => true,
                            'roles' => ['admin', 'supplier', 'manager', 'salesperson'],
                        ],
                    ],
                ]
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
    public function actionCreate()
    {
        $model = new Sale();
        $userId = Yii::$app->user->id;
        $seller = UserExtra::findOne(['user_id' => $userId]);
        $activities = Activity::getSupplierActivityNames($seller->supplier);
        $clients = Sale::getAllClients();
        $clientsMap = ArrayHelper::map($clients, 'id', 'username');
        $model->seller_id = $seller->id;
        $model->localsellpoint_id = $seller->localsellpoint_id;
        $calendar_id = $model->calendar_id;
        if (!$seller || !$seller->supplier) {
            throw new NotFoundHttpException('Supplier information is missing.');
        }
        if ($this->request->isPost) {
            $model->load($this->request->post());
            if ($model->buyer === null) {
                throw new BadRequestHttpException('Buyer information is missing.');
            }
            if ($model->calendar_id === null) {
                throw new BadRequestHttpException('Calendar ID is required.');
            }
            $activity = Activity::findOne($this->request->post('Sale')['activity_id']);
            if (!$activity) {
                throw new NotFoundHttpException('Activity not found.');
            }
            $model->purchase_date = date('Y-m-d H:i:s', time());
            $model->total = $activity->priceperpax * $model->quantity;
            if ($model->save()) {
                Sale::createBooking($activity, $model->buyer, $model->calendar_id, $model->quantity);
                $booking = Booking::find()
                    ->where(['activity_id' => $activity->id, 'user_id' => $model->buyer])
                    ->one();
                $invoice = Invoice::createInvoiceBackend($userId, $model->id, $booking->id);
                $qrCode = $this->generateQrCodeSale($model);
                $qrCodeImage = $qrCode->writeString(); // QR Code as PNG string

                $this->createTicketBackend($model, $qrCode, $booking->id);
                $content = $this->renderPartial('receipt', ['sale' => $model, 'invoice' => $invoice]);
                $pdfContent = Cart::generatePdf($content);
                $buyerEmail = $model->buyer0->email;
                if (Yii::$app->mailer->compose()
                    ->setFrom('waypinpoint@gmail.com')
                    ->setTo($buyerEmail)
                    ->setSubject('Your Booking Receipt and Ticket')
                    ->setTextBody('Your receipt and ticket are attached.')
                    ->setHtmlBody('<b>Thank you for your booking! Your receipt and ticket are attached.</b>')
                    ->attachContent($pdfContent, ['fileName' => 'receipt.pdf', 'contentType' => 'application/pdf'])
                    ->attachContent($qrCodeImage, ['fileName' => 'ticket.png', 'contentType' => 'image/png'])
                    ->send()) {
                    Yii::$app->session->setFlash('success', 'Ticket and Receipt sent by Email! You can find them in your personal area as well to download');
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to send ticket and receipt by email! Don\'t worry you can find them in your personal area to download');
                }
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

    public function actionPrint($sale_id)
    {
        $invoice = Invoice::find()->where(['sale_id' => $sale_id])->one();
        $content = $this->renderPartial('invoice', ['invoice' => $invoice]);

        $pdf = new Mpdf();
        $pdf->WriteHTML($content);
        return Yii::$app->response->sendContentAsFile($pdf->Output('', 'S'), "receipt_{$invoice->sale->purchase_date}.pdf", [
            'mimeType' => 'application/pdf',
        ]);
    }

    public static function generateQrCodeSale($sale)
    {
        $qrCodeData = "User: " . $sale->buyer0->username . ", Activity: " . $sale->activity->description . ", Price: " . $sale->total . ", Number of tickets: " . $sale->quantity;
        $qrCode = (new QrCode($qrCodeData))
            ->setSize(250)
            ->setMargin(5)
            ->setBackgroundColor(51, 153, 255);
        return $qrCode;
    }

    public static function createTicketBackend($sale, $qrCode, $bookingId)
    {
        $model = new Ticket();
        $model->activity_id = $sale->activity_id;
        $model->participant = $sale->buyer;
        $model->booking_id = $bookingId;
        $model->qr = $qrCode->getText();
        $model->status = 0;
        if ($model->save()) {
            return true;
        } else {
            return false;
        }
    }
}
