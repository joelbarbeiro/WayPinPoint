<?php

namespace frontend\controllers;

use common\models\Activity;
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
 * TicketController implements the CRUD actions for Ticket model.
 */
class TicketController extends Controller
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
     * Lists all Ticket models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $query = Ticket::find()
            ->where(['participant' => Yii::$app->user->id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    /**
     * Displays a single Ticket model.
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
     * Creates a new Ticket model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Ticket();

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
     * Updates an existing Ticket model.
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
     * Deletes an existing Ticket model.
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
     * Finds the Ticket model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Ticket the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ticket::findOne(['id' => $id])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public static function actionPrint($id)
    {
        $ticket = Ticket::findOne($id);
        if(!$ticket){
            Yii::$app->session->setFlash('error', 'Ticket not found');
        }
        $ticket = Ticket::findOne($id);
        $userId = $ticket->participant;
        $user = User::findOne($userId);
        $activity = Activity::findOne($ticket->activity_id);
        if (!$user || !$activity) {
            Yii::$app->session->setFlash('error', 'Related user or activity not found');
            return null;
        }
        $qrCode = self::generateQrCode($user, $activity);
        $content =  Yii::$app->controller->renderPartial('printticket', [
            'user' => $user,
            'activity' => $activity,
            'qrCode' => $qrCode,
        ]);
        self::generatePdf($content, $user, $activity);
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

    public static function generatePdf($content, $user, $activity)
    {
        $pdf = new Mpdf();
        $pdf->WriteHTML($content);
        return Yii::$app->response->sendContentAsFile($pdf->Output('', 'S'), "receipt_{$user->username}_{$activity->description}.pdf", [
            'mimeType' => 'application/pdf',
        ]);
    }
}