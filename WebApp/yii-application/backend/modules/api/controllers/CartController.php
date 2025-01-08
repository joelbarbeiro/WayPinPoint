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

//    public function behaviors()
//    {
//        $behaviors = parent::behaviors();
//        $behaviors['authenticator'] = [
//            'class' => HttpBasicAuth::className(), // ou QueryParamAuth::className(),
//            'except' => ['index', 'view'],
//            'auth' => [$this, 'authintercept']
//        ];
//        return $behaviors;
//    }
//
//    public function authintercept($username, $password)
//    {
//        $user = User::findByUsername($username);
//        if ($user && $user->validatePassword($password)) {
//            $this->user = $user; //Guardar user autenticado         r
//            return $user;
//        }
//        throw new \yii\web\ForbiddenHttpException('Error auth'); //403
//    }

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
                'product_id' => $cart['activity']['id'] ?? 'Unknown Product',
                'quantity' => $cart['quantity'],
                'user_id' => $cart['user']['id'] ?? 'Unknown User',
                'status' => $cart['status'],
                'calendar_id' => $cart['calendar']['id'],
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

    public function actionAddcart()
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
        try {

            $cart = Cart::findOne(['id' => $id]);
            if (!$cart) {
                throw new \Exception("Cart not found for ID: $id");
            }

            $bookingId = Booking::createBooking($cart);
            if (!$bookingId) {
                throw new \Exception("Failed to create booking for Cart ID: $id");
            }

            $saleId = Sale::createSale($cart);
            if (!$saleId) {
                throw new \Exception("Failed to create sale for Cart ID: $id");
            }

            $invoiceCreated = Invoice::createInvoice($cart, $saleId, $bookingId);
            if (!$invoiceCreated) {
                throw new \Exception("Failed to create invoice for Cart ID: $id");
            }

            $qrCode = Cart::generateQrCode($cart);
            Ticket::createTicket($cart, $qrCode, $bookingId);

            $cart->status = 1;
            if (!$cart->save()) {
                throw new \Exception("Failed to save Cart ID: $id. Errors: " . json_encode($cart->getErrors()));
            }

            $content = $this->renderPartial('receiptApi', ['cart' => $cart]);
            $pdfContent = Cart::generatePdf($content);
            $qrCodeImage = $qrCode->writeString();

            $mailSent = Yii::$app->mailer->compose()
                ->setFrom('waypinpoint@gmail.com')
                ->setTo($cart->user->email)
                ->setSubject('Your Booking Receipt and Ticket')
                ->setTextBody('Your receipt and ticket are attached.')
                ->setHtmlBody('<b>Thank you for your booking! Your receipt and ticket are attached.</b>')
                ->attachContent($pdfContent, ['fileName' => 'receipt.pdf', 'contentType' => 'application/pdf'])
                ->attachContent($qrCodeImage, ['fileName' => 'ticket.png', 'contentType' => 'image/png'])
                ->send();

            if (!$mailSent) {
                throw new \Exception("Failed to send email for Cart ID: $id");
            }
            return[
                'status' => 'success',
                'message' => 'Ticket and receipt sent to your email'
            ];
        } catch (\Exception $e) {
            Yii::error($e->getMessage(), __METHOD__);
            \Yii::$app->response->statusCode = 400;
            throw new \Exception('Failed to Checkout: ' . json_encode($e->getMessage()));
        }
    }
}
