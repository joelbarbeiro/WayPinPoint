<?php

namespace backend\modules\api\controllers;

use common\models\User;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;

/**
 * Default controller for the `api` module
 */
class ReviewController extends ActiveController
{

    public $modelClass = 'frontend\models\Review';

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
            $this->user = $user; //Guardar user autenticado
            return $user;
        }
        throw new \yii\web\ForbiddenHttpException('Error auth'); //403
    }

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
        return $recs;
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

        $reviewModel->user_id = $postData['user_id'];
        $reviewModel->activity_id = $postData['activity_id'];
        $reviewModel->score = $postData['score'];
        $reviewModel->message = $postData['message'];
        $reviewModel->created_at = time();
        $reviewModel->save();
        return $reviewModel;
    }

}