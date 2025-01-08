<?php

namespace backend\controllers;

use backend\models\Localsellpoint;
use backend\models\RegisterForm;
use common\models\Activity;
use common\models\Booking;
use common\models\Category;
use common\models\Invoice;
use common\models\LoginForm;
use common\models\Sale;
use common\models\User;
use common\models\UserExtra;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['login', 'error', 'register'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'view'], // Adjust actions as needed
                        'allow' => true,
                        'roles' => ['admin'], // Ensure this matches the role returned by getRole()
                    ],
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'view'], // Backoffice actions
                        'allow' => false,
                        'roles' => ['client'], // Explicitly deny client access to backoffice
                    ],
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'login', 'view'],
                        'allow' => true,
                        'roles' => ['admin', 'supplier', 'manager', 'salesperson', 'guide'],
                    ],
                    [
                        'actions' => ['index', 'create', 'update', 'delete', 'view', 'update-status'],
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
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => \yii\web\ErrorAction::class,
                'layout' => 'blank'
            ],


        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $userId = Yii::$app->user->id;
        $user = User::findOne(['id' => $userId]);
        $role = $user->getRole();
        $userExtra = $user->getUserExtra();
        $supplierId = $userExtra->supplier;
        $localsellpointId = $userExtra->localsellpoint_id;
        $activities = Activity::find()->where(['user_id' => $supplierId])->asArray()->all();

        $numActivities = Activity::getActivityCount($supplierId);
        $numCategories = Category::getCategoryCount();
        $numLocalShops = Localsellpoint::getLocalsellpointCount($supplierId);
        $numEmployees = UserExtra::getUserExtraCount($supplierId);
        $numClients = User::getClientsCount();
        $numSuppliers = User::getSupplierCount();
        $numManagers = User::getManagerCount();
        $numSellers = User::getSellerCount();
        $numGuides = User::getGuideCount();
        $numSalesSeller = Sale::getSalesSellerCount($userId);
        $numSalesShop = Sale::getSalesShopCount($localsellpointId);
        $numInvoices = Invoice::getInvoiceCount();
        $numBookings = Booking::getBookingsCount($userId);
        $totalInvoiced = Sale::getTotalSales();
        $shopInvoiced = Sale::getSalesForShop($localsellpointId);
        $dayInvoiced = Sale::getSalesTotalForDay();


        return $this->render('index', [
            'user' => $user,
            'role' => $role,
            'activities' => $activities,
            'numActivities' => $numActivities,
            'numCategories' => $numCategories,
            'numLocalShops' => $numLocalShops,
            'numEmployees' => $numEmployees,
            'numClients' => $numClients,
            'numSuppliers' => $numSuppliers,
            'numManagers' => $numManagers,
            'numSellers' => $numSellers,
            'numGuides' => $numGuides,
            'numSalesSeller' => $numSalesSeller,
            'numSalesShop' => $numSalesShop,
            'numInvoices' => $numInvoices,
            'numBookings' => $numBookings,
            'totalInvoiced' => $totalInvoiced,
            'shopInvoiced' => $shopInvoiced,
            'dayInvoiced' => $dayInvoiced
        ]);
    }

    public function actionRegister()
    {
        $model = new RegisterForm();

        $this->layout = 'blank';

        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            Yii::$app->session->setFlash('success', 'Registration successful. You can now log in.');
            return $this->redirect(['site/login']);
        }

        $this->layout = 'blank';

        return $this->render('register', [
            'model' => $model,
        ]);
    }

    /**
     * Login action.
     *
     * @return string|Response
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $this->layout = 'blank';

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionError()
    {
        $exception = Yii::$app->errorHandler->exception;
        if ($exception !== null) {
            if ($exception->statusCode === 403) {
                // Render a custom 403 view
                return $this->render('@backend/views/loginerror', ['exception' => $exception]);
            }
            return $this->render('@backend/views/error', ['exception' => $exception]);
        }
        return $this->render('@backend/views/error');
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
