<?php

namespace backend\modules\api\controllers;

use \common\models\Activity;
use common\models\Calendar;
use common\models\Category;
use common\models\Time;
use common\models\User;
use common\models\UserExtra;
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
            \Yii::$app->response->statusCode = 200;
            return $activities;
        }
        \Yii::$app->response->statusCode = 400;
        return ['error' => 'Activity not found'];
    }

    public function actionActivityview($id)
    {
        $activity = Activity::getActivityview($id);
        if ($activity) {
            \Yii::$app->response->statusCode = 200;
            return ['activity' => $activity];
        }
        \Yii::$app->response->statusCode = 400;
        return ['error' => 'Activity not found'];
    }

    public function actionCreateactivity()
    {
        $activity = new Activity();
        if ($activity->load(Yii::$app->request->post(), '')) {
            if (Activity::createActivity($activity)) {
                \Yii::$app->response->statusCode = 200;
                return $activity;
            }
        }
        \Yii::$app->response->statusCode = 400;
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
                \Yii::$app->response->statusCode = 200;
                return $activity;
            }
        }
        \Yii::$app->response->statusCode = 400;
        return [
            'status' => 'error',
            'message' => json_encode($activity->errors),
        ];
    }

    public function actionDeleteactivity($id)
    {
        $activity = new Activity();
        if ($activity->deleteActivity($id)) {
            \Yii::$app->response->statusCode = 200;
            return [
                'activity' => 'success',
                'message' => 'Update successfully',
            ];
        }
        \Yii::$app->response->statusCode = 400;
        return [
            'status' => 'error',
            'message' => json_encode($activity->errors),
        ];
    }
    public function actionCalendar()
    {
        $calendar = Calendar::getCalendar();
        if ($calendar) {
            \Yii::$app->response->statusCode = 200;
            return $calendar;
        }
        \Yii::$app->response->statusCode = 400;
        return ['error' => 'Calendar not found'];
    }

    public function actionTime()
    {
        $time = Time::getTimes();
        if ($time) {
            \Yii::$app->response->statusCode = 200;
            return $time;
        }
        \Yii::$app->response->statusCode = 400;
        return ['error' => 'Calendar not found'];
    }
    public function actionCategory()
    {
        $category = Category::getCategory();
        if ($category) {
            \Yii::$app->response->statusCode = 200;
            return $category;
        }
        \Yii::$app->response->statusCode = 400;
        return ['error' => 'Calendar not found'];
    }

    public function actionPhoto()
    {
        $postData = \Yii::$app->request->post();
        $id = $postData['id'];

        $photoFile = \yii\web\UploadedFile::getInstanceByName('photoFile');

        if (!$photoFile) {
            \Yii::$app->response->statusCode = 400;
            return ['status' => 'error', 'message' => 'No file uploaded'];
        }

        $activity = Activity::findOne($id);
        if (!$activity) {
            \Yii::$app->response->statusCode = 400;
            return ['status' => 'error', 'message' => 'Activity not found'];
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($photoFile) {
                $photoDirBackend = Yii::getAlias('@backend/web/img/activity/photos/' . $id . '/');

                if (!is_dir($photoDirBackend)) {
                    mkdir($photoDirBackend, 0755, true);
                }

                $uniqueFilename = uniqid() . '.' . $photoFile->extension;

                $photoPathBackend = $photoDirBackend . $uniqueFilename;

                if (!$photoFile->saveAs($photoPathBackend)) {
                    $transaction->rollBack();
                    \Yii::$app->response->statusCode = 400;
                    throw new \Exception('Failed to save photo data');
                } else{
                    $transaction->commit();
                    return [
                        'status' => 'success',
                        'photo' => $uniqueFilename
                    ];
                }
            } else{
                \Yii::$app->response->statusCode = 400;
                throw new \Exception('Empty photo file');
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::$app->response->statusCode = 500;
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function actionPictures($id)
    {
        $photosDirectory = Yii::getAlias('@backend/web/img/activity/photos/' . $id . '/') ;

        if (!is_dir($photosDirectory)) {
            return $this->asJson([]);
        }

        $files = scandir($photosDirectory);
        $photoUrls = [];

        foreach ($files as $file) {
            if (is_file($photosDirectory . '/' . $file)) {
                $photoUrls[] = Yii::$app->request->hostInfo . "/img/activity/photos/" . $id . "/" . $file;
            }
        }

        return $this->asJson($photoUrls);
    }
}