<?php

namespace backend\controllers;

use common\models\Activity;
use common\models\ActivitySearch;
use common\models\Calendar;
use common\models\Category;
use common\models\Date;
use common\models\Time;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ActivityController implements the CRUD actions for activity model.
 */
class ActivityController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'actions' => ['login', 'error', 'register'],
                            'allow' => true,
                        ],
                        [
                            'actions' => ['index', 'create', 'update', 'delete', 'view'], // Backoffice actions
                            'allow' => false,
                            'roles' => ['client'], // Explicitly deny client access to backoffice
                        ],
                        [
                            'actions' => ['index', 'create', 'update', 'delete', 'view'],
                            'allow' => true,
                            'roles' => ['admin', 'supplier', 'manager', 'salesperson', 'guide'],
                        ],
                        [
                            'actions' => ['logout', 'index'],
                            'allow' => true,
                            'roles' => ['@'],
                        ],

                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all activity models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $userId = Yii::$app->user->id;

        $searchModel = new Activity();
        $dataProvider = $searchModel->getSupplierActivities($userId);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);

    }

    /**
     * Displays a single Activity model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $userId = Yii::$app->user->id;

        $model = new Activity();
        $activity = $model->getActivity($id, $userId);

        if (!$activity) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render('view', [
            'model' => $activity,
        ]);
    }

    /**
     * Creates a new Activity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Activity();

        $hoursList = $model->getTimeList();

        $categoriesId = Category::find()
            ->select(['id', 'description'])
            ->asArray()
            ->all();

        $categories = ArrayHelper::map($categoriesId, 'id', 'description');

        if ($model->load($this->request->post())) {
            $getDateTimes = $model->getCalendarArray();
            $model->uploadPhoto();
            $model->user_id = Yii::$app->user->id;

            if ($model->validate() && $model->save()) {
                foreach ($getDateTimes as $dateVal => $timeId) {
                    $date = new Date();
                    $date->date = $dateVal;
                    $date->save();
                    foreach ($timeId as $time) {
                        $calendarModel = new Calendar();
                        $calendarModel->activity_id = $model->id;
                        $calendarModel->date_id = $date->id;
                        $calendarModel->time_id = $time;
                        $calendarModel->save();
                    }
                }
            }
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'categories' => $categories,
            'hoursList' => $hoursList,
        ]);
    }

    /**
     * Updates an existing Activity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = new Activity();
        $userId = Yii::$app->user->id;

        $model = $model->getActivity($id, $userId);
        $hoursList = $model->getTimeList();

        if ($model->load($this->request->post())) {
            $getDateTimeUpdate = $model->setCalendar($model->id, $model->date, $model->hour);
            $model->uploadPhoto();
            if ($model->validate() && $model->save()) {
                foreach ($getDateTimeUpdate as $dateVal => $timeId) {
                    $date = $model->getDateIfExists($dateVal);
                    if($date == null){
                        $date = new Date();
                        $date->date = $dateVal;
                        $date->save();
                    }
                    foreach ($timeId as $time) {
                        $calendarModel = new Calendar();
                        $calendarModel->activity_id = $model->id;
                        $calendarModel->date_id = $date->id;
                        $calendarModel->time_id = $time;
                        $calendarModel->save();
                    }
                }
            }
            return $this->redirect(['index']);
        }
        return $this->render('update', [
            'model' => $model,
            'hoursList' => $hoursList,
        ]);
    }

    /**
     * Deletes an existing Activity model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model != null) {
            $model->status = 0;
            $model->save(false);
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Activity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Activity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Activity::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
