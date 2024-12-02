<?php

namespace backend\models;

use common\models\User;
use common\models\UserExtra;
use Yii;
use yii\db\Query;

class RegisterForm extends \yii\db\ActiveRecord
{
    public $username;
    public $email;
    public $password;
    public $phone;
    public $address;
    public $nif;
    public $localsellpoint;
    public $role;
    public $photoFile;
    public $photo;

    public static function tableName()
    {
        return 'user';
    }

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

            ['address', 'required'],
            ['address', 'string'],

            ['nif', 'required'],
            ['nif', 'integer', 'min' => 100000000, 'max' => 999999999],

            [['photo'], 'string', 'max' => 250],
            [['photoFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    public function register()
    {
        $nifExists = (new Query())
            ->from('userextra')
            ->where(['nif' => $this->nif])
            ->exists();

        if ($nifExists) {
            throw new \yii\web\BadRequestHttpException("NIF already exists.");
        }

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
                $userExtra->user_id = $user->id;
                $userExtra->address = $this->address;
                $userExtra->nif = $this->nif;
                $userExtra->uploadUserPhoto($this);
                $userExtra->save(false);
            }

            // the following three lines were added:
            $auth = \Yii::$app->authManager;
            $clientRole = $auth->getRole('supplier');
            $auth->assign($clientRole, $user->getId());

            return $user;
        }
        return null;
    }
}



