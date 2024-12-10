<?php

namespace backend\modules\api\controllers;

use common\models\Activity;
use common\models\Cart;
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

    public function actionProducts($id)
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
                'cart_id' => $cart['id'],
                'product_id' => $cart['activity']['name'] ?? 'Unknown Product',
                'quantity' => $cart['quantity'],
                'user' => $cart['user']['username'] ?? 'Unknown User',
                'status' => $cart['status'],
                'date' => $cart['calendar']['date']['date'] ?? 'Unknown Date',
                'time' => $cart['calendar']['time']['hour'] ?? 'Unknown Time',
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

    public function actionAddtocart($id)
    {
        $userId = Yii::$app->user->id;
        if (!$userId) {
            return ['error' => 'User not logged in'];
        }
        $postData = Yii::$app->request->post();
        $cart = new Cart();
        $cart->product_id = $id;
        $cart->user_id = $userId;
        $cart->quantity = $postData['quantity'] ?? null;
        $cart->status = 0;
        $cart->calendar_id = $postData['calendar_id'] ?? null;

        if ($cart->save()) {
            return [
                'success' => true,
                'message' => 'Item successfully added to cart',
                'cart_id' => $cart->id,
            ];
        } else {
            return [
                'success' => false,
                'errors' => $cart->getErrors(),
            ];
        }
    }

    public function actionUpdatecart($id)
    {
        $userId = Yii::$app->user->id;
        if (!$userId) {
            return ['error' => 'User not logged in'];
        }
        $cartModel = new $this->modelClass;
        $cartItems = $cartModel::findOne(['id' => $id, 'user_id' => $userId]);
        if (!$cartItems) {
            return [
                'success' => false,
                'message' => 'Cart item not found or does not belong to the user',
            ];
        }

        $postData = Yii::$app->request->bodyParams;

        $cartItems->quantity = $postData['quantity'] ?? $cartItems->quantity;
        $cartItems->calendar_id = $postData['calendar_id'] ?? $cartItems->calendar_id;
        if ($cartItems->save()) {
            return [
                'success' => true,
                'message' => 'Cart item successfully updated',
                'cart_id' => $cartItems->id,
                'updated_fields' => [
                    'quantity' => $cartItems->quantity,
                    'calendar_id' => $cartItems->calendar_id,
                ],
            ];
        } else {
            return [
                'success' => false,
                'errors' => $cartItems->getErrors(),
            ];
        }


    }


}

