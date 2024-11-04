<?php


namespace backend\models;

use common\models\User;
use Yii;
use yii\db\ActiveRecord;
class RoleRegisterForm extends ActiveRecord
{
    public $username;
    public $email;
    public $password;
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
            ['role', 'required'],
            ['role', 'in', 'range' => ['manager', 'guide', 'salesperson']],
        ];
    }

    public function roleRegister()
    {
        if ($this->validate()) {
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->status = 10;
            $user->save(false);
            $auth = \Yii::$app->authManager;
            $clientRole = $auth->getRole($this->role);
            $auth->assign($clientRole, $user->getId());

            return $user;
        }
        return null;
    }
}