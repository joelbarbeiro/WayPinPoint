<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

class BackendUserForm extends ActiveRecord
{
    public $username;
    public $email;
    public $password;
    public $role;

    public function rules()
    {
        return[
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => Yii::$app->params['user.passwordMinLength']],
            ['role', 'required'],
            ['role', 'in' , 'range'=> ['admin', 'user', 'supplier']],
        ];
    }

    public function register()
    {
        $model = new RegisterForm();
        if ($model->load($this->request->post()) && $model->register()) {
            $auth = $this->authManager;
            $role = $auth->getRole($model->role);
            $auth->assign($role, $model->id);
            $this->session->setFlash('success', 'User registered with role: ' . $model->role);
            return $this->redirect(['index']);
        }
        return $this->render('backendregister', ['model' => $model]);
    }
}