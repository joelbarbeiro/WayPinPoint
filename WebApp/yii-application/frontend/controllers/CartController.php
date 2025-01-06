<?php

namespace frontend\controllers;

use common\models\Activity;
use common\models\Booking;
use common\models\Calendar;
use common\models\Cart;
use common\models\Invoice;
use common\models\Sale;
use common\models\Ticket;
use Da\QrCode\QrCode;
use Dompdf\Dompdf;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


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
        $dataProvider = Cart::find()
            ->where(['user_id' => Yii::$app->user->id])
            ->andWhere(['status' => 0])
            ->all();


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
    public function actionCreate($activityId, $calendarId)
    {
        $model = new Cart();
        $activity = Activity::findOne($activityId);
        $userId = Yii::$app->user->id;
        $model->calendar_id = $calendarId;
        $calendar = Calendar::findOne($calendarId);

        if($userId == null)
        {
            Yii::$app->session->setFlash('error', 'Need to be logged in to buy tickets');
            return $this->redirect(['activity/index']);
        }
        $model->user_id = $userId;
        $model->product_id = $activityId;
        if ($model->load($this->request->post())) {
            $totalTicketsBooked = Booking::getTotalTicketsByActivity($activityId);
            if (($model->quantity + $totalTicketsBooked) <= $activity->maxpax) {
                if ($model->save()) {
                    return $this->redirect(['cart/index', 'user_id' => $model->user_id, 'product_id' => $model->product_id, 'calendar_id' => $model->calendar_id]);
                }
            } else {
                Yii::$app->session->removeFlash('error');
                Yii::$app->session->setFlash('error', 'Not enough tickets!');
            }
        }

        return $this->render('create', [
            'model' => $model,
            'calendarId' => $calendarId,
            'activityName' => $activity->name,
            'calendarDate' => $calendar->date->date,
            'calendarHour' => $calendar->time->hour,
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

    public function actionCheckout($id)
    {
        $cart = Cart::findOne(['id' => $id]);
        if ($bookingId = Booking::createBooking($cart)) {
            if ($saleId = Sale::createSale($cart)) {
                if (Invoice::createInvoice($cart, $saleId, $bookingId)) {
                    $qrCode = Cart::generateQrCode($cart->user, $cart->activity);
                    Ticket::createTicket($cart, $qrCode);

                    $cart->status = 1;
                    if ($cart->save()) {
                        Yii::$app->session->setFlash('success', 'Checkout completed successfully.');
                    } else {
                        Yii::$app->session->setFlash('error', 'Failed to Checkout');
                    }
                    // Generate PDF content
                    $content = $this->renderPartial('receipt', ['cart' => $cart]);
                    $pdfContent = Cart::generatePdf($content);
                    $qrCodeImage = $qrCode->writeString(); // QR Code as PNG string

                    // Send email with attachments
                    if (Yii::$app->mailer->compose()
                        ->setFrom('waypinpoint@gmail.com')
                        ->setTo($cart->user->email)
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
                    return $this->redirect(['activity/index']);
                }
            }
        }
    }

}
