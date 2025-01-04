<?php

namespace backend\modules\api\controllers;

use common\models\User;
use frontend\models\Review;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;

/**
 * Default controller for the `api` module
 */
class ReviewController extends ActiveController
{

    public $modelClass = 'frontend\models\Review';

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
//            $this->user = $user; //Guardar user autenticado
//            return $user;
//        }
//        throw new \yii\web\ForbiddenHttpException('Error auth'); //403
//    }

    public function actionCount()
    {
        $reviewModel = new $this->modelClass;
        $recs = $reviewModel::find()->all();
        return ['count' => count($recs)];
    }

    public function actionActivity($id)
    {
        $reviewModel = new $this->modelClass;
        $recs = $reviewModel->findAll(['activity_id' => $id]);

        $response = [];
        foreach ($recs as $rec) {
            $user = User::findOne(['id' => $rec->user_id]);
            $response[] = [
                'id' => $rec->id,
                'user_id' => $rec->user_id,
                'activity_id' => $rec->activity_id,
                'score' => $rec->score,
                'message' => $rec->message,
                'created_at' => $rec->created_at,
                'creator' => $user ? $user->username : null
            ];
        }

        return $response;
    }

    public function actionUser($id)
    {
        $reviewModel = new $this->modelClass;
        $recs = $reviewModel->findAll(['user_id' => $id]);
        return $recs;
    }

    public function actionNew()
    {
        $postData = \Yii::$app->request->post();
        $reviewModel = new $this->modelClass;
        $user = User::findOne(['id' => $postData['user_id']]);

        $reviewModel->user_id = $postData['user_id'];
        $reviewModel->activity_id = $postData['activity_id'];
        $reviewModel->score = $postData['score'];
        $reviewModel->message = $postData['message'];
        $reviewModel->created_at = time();
        $reviewModel->save();

        return [
            'id' => $reviewModel->id,
            'user_id' => $reviewModel->user_id,
            'activity_id' => $reviewModel->activity_id,
            'score' => $reviewModel->score,
            'message' => $reviewModel->message,
            'created_at' => $reviewModel->created_at,
            'creator' => $user->username
        ];
    }

    public function actionEdit()
    {
        $postData = \Yii::$app->request->post();
        $reviewModel = Review::findOne(['id' => $postData['id']]);
        $user = User::findOne(['id' => $postData['user_id']]);

        $reviewModel->user_id = $postData['user_id'];
        $reviewModel->activity_id = $postData['activity_id'];
        $reviewModel->score = $postData['score'];
        $reviewModel->message = $postData['message'];
        $reviewModel->created_at = time();
        $reviewModel->save();

        return [
            'id' => $reviewModel->id,
            'user_id' => $reviewModel->user_id,
            'activity_id' => $reviewModel->activity_id,
            'score' => $reviewModel->score,
            'message' => $reviewModel->message,
            'created_at' => $reviewModel->created_at,
            'creator' => $user->username
        ];
    }
}