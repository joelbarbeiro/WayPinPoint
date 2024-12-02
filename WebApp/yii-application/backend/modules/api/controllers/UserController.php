<?php

namespace backend\modules\api\controllers;

use common\models\User;
use yii\rest\ActiveController;

/**
 * Default controller for the `api` module
 */
class UserController extends ActiveController
{
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

    public function actionDelbyusername($username)
    {
        $userModel = new $this->modelClass;
        $recs = $userModel::deleteAll(['username' => $username]);
        return $recs;
    }

}