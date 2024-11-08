<?php


namespace backend\models;

use common\models\User;
use common\models\UserExtra;
use Yii;
use yii\db\ActiveRecord;

class RoleRegisterForm extends ActiveRecord
{
    public $username;
    public $email;
    public $password;
    public $phone;
    public $role;

    public function rules()
    {
        return [
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

            ['phone', 'required'],
            ['phone', 'string'],

            ['role', 'required'],
            ['role', 'in', 'range' => ['manager', 'guide', 'salesperson']],
        ];
    }

    public function roleRegister()
    {
        $supplierId = Yii::$app->user->id;
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->status = 10;
            if ($user->save(false)) {
                $userExtra = new UserExtra();
                $userExtra->phone = $this->phone;
                $userExtra->user = $user->id;
                $userExtra->supplier = $supplierId;
                $userExtra->save(false);
            }
            $auth = \Yii::$app->authManager;
            $clientRole = $auth->getRole($this->role);
            $auth->assign($clientRole, $user->getId());

            return $user;
        }

        return null;
    }
}