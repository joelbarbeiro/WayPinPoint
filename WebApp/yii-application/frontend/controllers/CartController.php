<?php

namespace frontend\controllers;

use common\models\Activity;
use common\models\Booking;
use common\models\Cart;
use common\models\Invoice;
use common\models\Sale;
use common\models\Ticket;
use common\models\User;
use Da\QrCode\QrCode;
use Mpdf\Mpdf;
use Yii;
use yii\data\ActiveDataProvider;
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
        $query = Cart::find()->where(['user_id' => Yii::$app->user->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
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
        $activity = Activity::findOne($activityId);
        $userId = Yii::$app->user->id;
        $model->user_id = $userId;
        $model->product_id = $activityId;
        if ($model->load($this->request->post())) {
            if ($model->quantity < $activity->maxpax) {
                if ($model->save()) {
                    return $this->redirect(['view', 'user_id' => $model->user_id, 'product_id' => $model->product_id]);
                }
                dd($model->errors);

            }
        } else {
            Yii::$app->session->setFlash('Not enough tickets available');
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

    public function actionCheckout()
    {
        $userId = Yii::$app->request->post('user_id');
        $activityId = Yii::$app->request->post('activity_id');
        // Fetch user and activity data
        $user = User::findOne($userId);
        $activity = Activity::findOne($activityId);
        Booking::createBooking($activityId);
        Sale::createSale($activityId);
        Invoice::createInvoice($activityId);
        $qrCode = $this->generateQrCode($user, $activity);
        Ticket::createTicket($activityId, $qrCode);

        if (!$user || !$activity) {
            Yii::$app->session->setFlash('error', 'User or Activity not found.');
            return $this->redirect(['index']);
        }
        $content = $this->renderPartial('receipt', [
            'user' => $user,
            'activity' => $activity,
            'qrCode' => $qrCode,
        ]);
        $this->generatePdf($content, $user, $activity);
    }

    public static function generateQrCode($user, $activity)
    {
        $qrCodeData = "User: $user->username, Activity: $activity->description, Price: $activity->priceperpax"; //IGUALAR A VARIAVEL QR NO TICKET
        $qrCode = (new QrCode($qrCodeData))
            ->setSize(250)
            ->setMargin(5)
            ->setBackgroundColor(51, 153, 255);

        return $qrCode;
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
