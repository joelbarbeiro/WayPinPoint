<?php

namespace backend\modules\api\controllers;

use common\models\User;
use common\models\UserExtra;
use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\rest\ActiveController;

/**
 * Default controller for the `api` module
 */
class UserController extends ActiveController
{

    public $username;
    public $email;
    public $password;
    public $phone;
    public $address;
    public $nif;
    public $photoFile;
    public $photo;

    public $modelClass = 'common\models\User';

    public function actionCount()
    {
        $userModel = new $this->modelClass;
        $recs = $userModel::find()->all();
        return ['count' => count($recs)];
    }

    public function actionUsernames()
    {
        $userModel = new $this->modelClass;
        $recs = $userModel::find()->select(['username'])->all();
        return $recs;
    }

    public function actionExtras()
    {
        $id = \Yii::$app->user->id;
        return User::find()
            ->select([
                'user.id',
                'user.username',
                'user.email',
                'userextra.phone',
                'userextra.nif',
                'userextra.address',
                'userextra.photo',
                'userextra.supplier',
                'userextra.localsellpoint_id'
            ])
            ->leftJoin('userextra', 'user.id = userextra.user_id') // Join on the user ID
            ->where(['user.id' => $id])
            ->asArray()
            ->all();
    }

    public function actionEmployees()
    {
        $id = \Yii::$app->user->id;
        return User::find()
            ->select([
                'user.id',
                'user.username',
                'user.email',
                'userextra.phone',
                'userextra.nif',
                'userextra.address',
                'userextra.photo',
                'userextra.supplier',
                'userextra.localsellpoint_id'
            ])
            ->leftJoin('userextra', 'user.id = userextra.user_id') // Join on the user ID
            ->where(['supplier' => $id])
            ->asArray()
            ->all();
    }

    public function actionRegister()
    {
        $postData = \Yii::$app->request->post();

        $user = new User();
        $user->username = $postData['username'] ?? null;
        $user->email = $postData['email'] ?? null;
        $user->setPassword($postData['password'] ?? null);
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();
        $user->created_at = new Expression('NOW()');
        $user->updated_at = 0;
        $user->status = User::STATUS_ACTIVE;

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($user->validate() && $user->save()) {
                $userExtra = new UserExtra();
                $userExtra->user_id = $user->id;
                $userExtra->phone = $postData['phone'] ?? null;
                $userExtra->address = $postData['address'] ?? null;
                $userExtra->nif = $postData['nif'] ?? null;

                if (!empty($postData['photoFile'])) {
                    $userExtra->photo = $this->uploadUserPhoto($postData['photoFile']);
                }

                $auth = \Yii::$app->authManager;
                $clientRole = $auth->getRole('client');
                $auth->assign($clientRole, $user->getId());

                if ($userExtra->validate() && $userExtra->save()) {
                    $transaction->commit();
                    return [
                        'status' => 'success',
                        'message' => 'User and UserExtra created successfully.',
                        'user_id' => $user->id,
                    ];
                } else {
                    throw new \Exception('Failed to save UserExtra: ' . json_encode($userExtra->getErrors()));
                }
            } else {
                throw new \Exception('Failed to save User: ' . json_encode($user->getErrors()));
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            return [
                'status' => 'error',
                'message' => $e->getMessage(),
            ];
        }
    }

    public function actionLogin(){
        $postData = \Yii::$app->request->post();
        $user = User::findOne(['username' => $postData['username']]);
        if($user != null){
            if($user->validatePassword($postData['password'])){
                return [
                    'status' => 'success',
                    'message' => 'Login successful',
                    'token' => $user->verification_token,
                ];
            }
            return "User not validated";
        }
        return "User doesn't Exist";
    }

    public function actionEdituserextras($id)
    {
        $postData = \Yii::$app->request->post();

        $user = User::findOne($id);
        $userExtra = UserExtra::findOne(['user_id' => $user->id]);


        $nifExists = (new Query())
            ->from('userextra')
            ->where(['nif' => $this->nif])
            ->andWhere(['!=', 'user_id', $user->id])
            ->exists();

        if ($nifExists) {
            throw new \yii\web\BadRequestHttpException("NIF already exists.");
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$user) {
                throw new \Exception("User not found");
            }
            $user->username = $postData['username'] ?? $user->username;
            $user->email = $postData['email'] ?? $user->email;
            $user->updated_at = new Expression('NOW()');
            $userExtra->phone = $postData['phone'] ?? $userExtra->phone;
            $userExtra->address = $postData['address'] ?? $userExtra->address;
            $userExtra->nif = $postData['nif'] ?? $userExtra->nif;

            if (!empty($postData['photoFile'])) {
                $userExtra->photo = $this->uploadUserPhoto($postData['photoFile']);
            }

            if ($user->save(false) && $userExtra->save(false)) {
                $transaction->commit();
                return true;
            } else {
                $transaction->rollBack();
                return false;
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    public function actionDelbyusername($username)
    {
        $userModel = new $this->modelClass;
        $userModel::deleteAll(['username' => $username]);
    }

}