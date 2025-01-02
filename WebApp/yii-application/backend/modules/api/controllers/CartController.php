<?php

namespace backend\modules\api\controllers;

use common\models\Booking;
use common\models\Activity;
use common\models\Cart;
use common\models\Invoice;
use common\models\Sale;
use common\models\Ticket;
use common\models\User;
use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;

class CartController extends ActiveController
{
    public $user_id;
    public $product_id;
    public $quantity;
    public $status;
    public $calendar_id;

    public $modelClass = 'common\models\Cart';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(), // ou QueryParamAuth::className(),
            'except' => ['index', 'view'],
            'auth' => [$this, 'authintercept']
        ];
        return $behaviors;
    }

    public function authintercept($username, $password)
    {
        $user = User::findByUsername($username);
        if ($user && $user->validatePassword($password)) {
            $this->user = $user; //Guardar user autenticado         r
            return $user;
        }
        throw new \yii\web\ForbiddenHttpException('Error auth'); //403
    }

    public function actionCount()
    {
        $cartModel = new $this->modelClass;
        $recs = $cartModel::find()->all();
        return ['count' => count($recs)];
    }

    public function actionCart($id)
    {
        $cartModel = new $this->modelClass;
        $recs = $cartModel::findOne(['id' => $id]);
        return $recs;
    }

    public function actionBuyer($id)
    {
        $cartModel = new $this->modelClass;
        $cartItems = $cartModel::find()
            ->with(['activity', 'user', 'calendar.date', 'calendar.time'])
            ->where(['user_id' => $id])
            ->andWhere(['status' => '0'])
            ->asArray()
            ->all();
        $data = array_map(function ($cart) {
            return [
                'id' => $cart['id'],
                'product_id' => $cart['activity']['name'] ?? 'Unknown Product',
                'quantity' => $cart['quantity'],
                'user_id' => $cart['user']['id'] ?? 'Unknown User',
                'status' => $cart['status'],
                'date' => $cart['calendar']['date']['date'] ?? 'Unknown Date',
                'time' => $cart['calendar']['time']['hour'] ?? 'Unknown Time',
                'price' => $cart['activity']['priceperpax'],
            ];
        }, $cartItems);
        return $data;
    }

    public function actionStatus()
    {
        $cartModel = new $this->modelClass;
        $recs = $cartModel::findAll(['status' => 0]);
        return $recs;
    }

    public function actionDelete($id)
    {
        $cartModel = new $this->modelClass;
        $cartModel::deleteAll(['id' => $id]);
        return ['success' => true,
            'message' => 'Cart has been deleted'];
    }

    public function actionAddCart()
    {
        $cart = new Cart();
        if ($cart->load(Yii::$app->request->post(), '')) {
            if ($cart->save()) {
                return [
                    'success' => true,
                    'message' => 'Cart created successfully',
                    'id' => $cart->id,
                    'user_id' => $cart->user_id,
                    'product_id' => $cart->product_id,
                    'status' => $cart->status = 0,
                    'quantity' => $cart->quantity,
                    'calendar_id' => $cart->calendar_id,
                ];
            }
        }
        return [
            'status' => 'error',
            'message' => json_encode($cart->getErrors()),
        ];
    }
    public function actionUpdate($id)
    {

        $cartModel = new $this->modelClass;
        $cartItem = $cartModel::findOne($id);
        if (!$cartItem) {
            return [
                'success' => false,
                'message' => 'Cart item not found',
            ];
        }

        $postData = Yii::$app->request->bodyParams;
        if (isset($postData['quantity'])) {
            $cartItem->quantity = $postData['quantity'];
        }

        if ($cartItem->save()) {
            return [
                'success' => true,
                'message' => 'Cart quantity successfully updated',
                'cart_id' => $cartItem->id,
                'quantity' => $cartItem->quantity,
            ];
        } else {
            return [
                'success' => false,
                'errors' => $cartItem->getErrors(),
            ];
        }
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

                    $content = $this->renderPartial('receipt', ['cart' => $cart]);
                    $pdfContent = Cart::generatePdf($content);
                    $qrCodeImage = $qrCode->writeString();

                    if (Yii::$app->mailer->compose()
                        ->setFrom('waypinpoint@gmail.com')
                        ->setTo($cart->user->email)
                        ->setSubject('Your Booking Receipt and Ticket')
                        ->setTextBody('Your receipt and ticket are attached.')
                        ->setHtmlBody('<b>Thank you for your booking! Your receipt and ticket are attached.</b>')
                        ->attachContent($pdfContent, ['fileName' => 'receipt.pdf', 'contentType' => 'application/pdf'])
                        ->attachContent($qrCodeImage, ['fileName' => 'ticket.png', 'contentType' => 'image/png'])
                        ->send()) {
                        return [
                            'success' => true,
                            'message' => 'Ticket and receipt sent to your email',
                        ];
                    } else {
                        return [
                            'success' => false,
                            'message' => 'Could not complete purchase',
                        ];
                    }
                }
            }
        }
    }

}

