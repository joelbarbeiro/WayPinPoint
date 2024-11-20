<?php

namespace backend\controllers;

use common\models\Activity;
use common\models\ActivitySearch;
use common\models\Calendar;
use common\models\Dates;
use common\models\Time;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ActivitiesController implements the CRUD actions for Activities model.
 */
class ActivitiesController extends Controller
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
     * Lists all Activities models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ActivitySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $userId = Yii::$app->user->id;

        $dataProvider->query->joinWith('calendars')
            ->andWhere(['user_id' => $userId])
            ->andWhere(['activities.status' => 1])
            ->andWhere(['calendar.status' => 1]);

        $dataProvider->query->all();

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

        $model = Activity::find()
            ->joinWith('calendars')
            ->where([
                'activities.id' => $id,
                'activities.user_id' => $userId,
                'calendar.status' => 1
            ])
            ->one();

        if (!$model) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        return $this->render('view', [
            'model' => $model,
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

        $hoursQuery = Time::find()->select(['id', 'hour'])->asArray()->all();
        $hoursList = ArrayHelper::map($hoursQuery, 'id', 'hour');

        if ($model->load($this->request->post())) {
            $getDateTimes = $model->getCalendarArray();
            $model->uploadPhoto();
            $model->user_id = Yii::$app->user->id;

            if ($model->validate() && $model->save()) {
                foreach ($getDateTimes as $date => $timeId) {
                    $dates = new Dates();
                    $dates->date = $date;
                    $dates->save();
                    foreach ($timeId as $time) {
                        $calendarModel = new Calendar();
                        $calendarModel->activities_id = $model->id;
                        $calendarModel->date_id = $dates->id;
                        $calendarModel->time_id = $time;
                        $calendarModel->save();
                    }
                }
            }
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
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
        $model = $this->findModel($id);

        $hoursQuery = Time::find()->select(['id', 'hour'])->asArray()->all();
        $hoursList = ArrayHelper::map($hoursQuery, 'id', 'hour');

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'hourList' => $hoursList,
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
