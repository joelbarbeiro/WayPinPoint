<?php

namespace backend\controllers;

use common\models\LoginForm;
use common\models\User;
use Yii;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use backend\models\RegisterForm;
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
                        'actions' => ['login', 'error', 'register', 'roleassign'],
                        'allow' => true,
                        'roles' => ['supplier']
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
        return $this->render('index');
    }

    public function actionRegister()
    {
        $model = new RegisterForm();

        if ($model->load(Yii::$app->request->post()) && $model->register()) {
            Yii::$app->session->setFlash('success', 'Registration successful. You can now log in.');
            return $this->redirect(['site/login']);
        }

        return $this->render('register', [
            'model' => $model,
           ]);
    }
    public function actionRoleassign($id)
    {
        $user = User::findOne($id);
        if (!$user) {
            throw new NotFoundHttpException('User not found.');
        }

        // Fetch all users
        $users = User::find()->all();

        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        $currentRole = $auth->getRolesByUser($user->id);
        $currentRole = reset($currentRole)->name ?? null; // Get current role name

        if (Yii::$app->request->post()) {
            $roleName = Yii::$app->request->post('User')['role'];
            $role = $auth->getRole($roleName);
            $auth->revokeAll($user->id); // Remove previous roles
            $auth->assign($role, $user->id); // Assign the new role

            Yii::$app->session->setFlash('success', 'Role assigned successfully.');
            return $this->redirect(['index']);
        }

        return $this->render('assign-role', [
            'user' => $user,
            'users' => $users, // Pass all users to the view
            'roles' => $roles,
            'currentRole' => $currentRole,
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
