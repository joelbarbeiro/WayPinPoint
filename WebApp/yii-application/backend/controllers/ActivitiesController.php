<?php

namespace backend\controllers;

use backend\models\Activities;
use backend\models\ActivitiesSearch;
use backend\models\Calendar;
use backend\models\CalendarSearch;
use backend\models\Dates;
use backend\models\Times;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
        $searchModel = new ActivitiesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);
        $searchModelCalendar = new CalendarSearch();
        $calendar = $searchModelCalendar->search($this->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'otherDataProvider' => $calendar,
        ]);
    }

    /**
     * Displays a single Activities model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Activities model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {

        $model = new Activities();
        $calendarModel = new Calendar();
        $dateModel = new Dates();
        $timeModel = new Times();

        if ($model->load($this->request->post()) && $timeModel->load($this->request->post()) &&
                $dateModel->load($this->request->post()))
            {
            if ($model->save() && $timeModel->save() && $dateModel->save()) {
                $calendarModel->activities_id = $model->id;
                $calendarModel->date_id = $dateModel->id;
                $calendarModel->time_id = $timeModel->id;

                if ($calendarModel->save()) {
                    return $this->redirect(['view', 'id' => $model->id]);
                }
            }
        }


        return $this->render('create', [
            'model' => $model,
            'timeModel' => $timeModel,
            'dateModel' => $dateModel,
        ]);

    }

    /**
     * Updates an existing Activities model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Activities model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Activities model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Activities the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Activities::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}