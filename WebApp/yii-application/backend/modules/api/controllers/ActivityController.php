<?php

namespace backend\modules\api\controllers;

use \common\models\Activity;
use common\models\Calendar;
use common\models\Time;
use common\models\User;
use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\rest\ActiveController;

class ActivityController extends ActiveController
{
    public $modelClass = 'common\models\Activity';
    public $photoFile;
    public $hour = [];
    public $date = [];

    /*public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBasicAuth::className(),
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
    }*/

    public function actionActivities()
    {
        $activities = Activity::getActivities();
        if ($activities) {
            return ['activities' => $activities];
        }
        return ['error' => 'Activity not found'];
    }

    public function actionActivityview($id)
    {
        $activity = Activity::getActivityview($id);
        if ($activity) {
            return ['activity' => $activity];
        }
        return ['error' => 'Activity not found'];
    }

    public function actionCreateactivity()
    {
        $activity = new Activity();
        if ($activity->load(Yii::$app->request->post(), '')) {
            if (Activity::createActivity($activity)) {
                return [
                    'activity' => 'success',
                    'message' => 'Activity created successfully',
                ];
            }
        }
        return [
            'status' => 'error',
            'message' => json_encode($activity->errors),
        ];
    }

    public function actionUpdateactivity($id)
    {
        $activity = new Activity();
        if(Yii::$app->request->post()) {
            $activity = $activity->getActivityView($id);
            $activity->load(Yii::$app->request->post(), '');
            if ($activity->updateActivity($activity)) {
                return [
                    'activity' => 'success',
                    'message' => 'Update successfully',
                ];
            }
        }
        return [
            'status' => 'error',
            'message' => json_encode($activity->errors),
        ];
    }

    public function actionDeleteactivity($id)
    {
        echo $id;
        $activity = new Activity();
        if ($activity->deleteActivity($id)) {
            return [
                'activity' => 'success',
                'message' => 'Update successfully',
            ];
        }
        return [
            'status' => 'error',
            'message' => json_encode($activity->errors),
        ];
    }
    public function actionCalendar()
    {
        $calendar = Calendar::getCalendar();
        if ($calendar) {
            return ['calendar' => $calendar];
        }
        return ['error' => 'Calendar not found'];
    }

    public function actionTime()
    {
        $time = Time::getTimes();
        if ($time) {
            return ['time' => $time];
        }
        return ['error' => 'Calendar not found'];
    }
}