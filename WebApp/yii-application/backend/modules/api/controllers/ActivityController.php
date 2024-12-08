<?php

namespace backend\modules\api\controllers;

use common\models\Activity;
use Yii;
use yii\rest\ActiveController;

class ActivityController extends ActiveController
{

    public $modelClass = 'models\Activity';
    public $photoFile;
    public $hour = [];
    public $date = [];

    public function actionCount()
    {
        $activities = Activity::getActivities();
        if ($activities) {
            return ['count' => count($activities)];
        }
        return ['error' => 'Activity not found'];
    }

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
        // Devido a limitações do Yii, por não suportar um multipart/form no metodo PUT
        // o envio de dados para este metodo deve ser efetudado por POST
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
}