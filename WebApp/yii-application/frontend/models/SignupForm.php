<?php

namespace frontend\models;

use common\models\User;
use common\models\UserExtra;
use Yii;
use yii\base\Model;
use yii\db\Query;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $phone;
    public $address;
    public $nif;
    public $photoFile;
    public $photo;


    /**
     * {@inheritdoc}
     */
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

    /**
     * Signs user up.
     *
     * @return bool whether the creating new account was successful and email was sent
     * @throws \Exception
     */
//    public function signup()
//    {
//        if ($this->validate()) {
//            $user = new User();
//            $user->username = $this->username;
//            $user->email = $this->email;
//            $user->setPassword($this->password);
//            $user->generateAuthKey();
//            $user->status = 10;
//            $user->save(false);
//
//            // the following three lines were added:
//            $auth = \Yii::$app->authManager;
//            $clientRole = $auth->getRole('client');
//            $auth->assign($clientRole, $user->getId());
//
//            return $user;
//        }
//
//        return null;
//    }


    public function signup()
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
            $user->created_at = time();
            $user->updated_at = 0;
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
            $auth = \Yii::$app->authManager;
            $clientRole = $auth->getRole('client');
            $auth->assign($clientRole, $user->getId());

            return $user;
        }

        return null;
    }

    public function updateUser($userId)
    {
        $userExtra = UserExtra::findOne(['user_id' => $userId]);
        $user = $userExtra->user;

        $nifExists = (new Query())
            ->from('userextra')
            ->where(['nif' => $this->nif])
            ->andWhere(['!=', 'user_id', $user->id])
            ->exists();

        if ($nifExists) {
            throw new \yii\web\BadRequestHttpException("NIF already exists.");
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$user) {
                throw new \Exception("User not found");
            }

            $user->username = $this->username;
            $user->email = $this->email;
            if ($this->password) {
                $user->setPassword($this->password);
            }
            $user->updated_at = time();
            $userExtra->uploadUserPhoto($this);
            $userExtra->phone = $this->phone;
            $userExtra->address = $this->address;
            $userExtra->nif = $this->nif;

            if ($user->save(false) && $userExtra->save(false)) {
                $transaction->commit();
                return true;
            } else {
                $transaction->rollBack();
                return false;
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }


    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        return Yii::$app
            ->mailer
            ->compose(
                ['html' => 'emailVerify-html', 'text' => 'emailVerify-text'],
                ['user' => $user]
            )
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }
}
