<?php


namespace backend\models;

use common\models\User;
use common\models\UserExtra;
use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

class RoleRegisterForm extends ActiveRecord
{
    public $username;
    public $email;
    public $password;
    public $phone;
    public $address;
    public $nif;
    public $localsellpoint;
    public $role;
    /**
     * @var mixed|null
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
            ['nif', 'string'],

            ['localsellpoint', 'required'],
            ['localsellpoint', 'string'],

            ['role', 'required'],
            ['role', 'in', 'range' => ['manager', 'guide', 'salesperson']],
        ];
    }

    public function saveUserRoleAssignment($userExtraId, $localsellpointId)
    {
        $localsellpointUserextra = new LocalsellpointUserextra();
        $localsellpointUserextra->localsellpoint_id = $localsellpointId;
        $localsellpointUserextra->userextra_id = $userExtraId;
        $localsellpointUserextra->role = $this->role;

        return $localsellpointUserextra->save(false); // Save without validation if validations are in place in RoleRegisterForm
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
                $userExtra->user_id = $user->id;
                $userExtra->address = $this->address;
                $userExtra->nif = $this->nif;
                $userExtra->localsellpoint_id = $this->localsellpoint;
                $userExtra->supplier = $supplierId;
                if ($userExtra->save(false)) {
                    $this->saveUserRoleAssignment($userExtra->id, $this->localsellpoint);
                }
            }
            $auth = \Yii::$app->authManager;
            $clientRole = $auth->getRole($this->role);
            $auth->assign($clientRole, $user->getId());

            return $user;
        }

        return null;
    }

    public function roleUpdate($userId)
    {
        $userExtra = UserExtra::findOne($userId);
        $user = $userExtra->user;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$user) {
                throw new \Exception("User not found");
            }

            // Update user attributes
            $user->username = $this->username;
            $user->email = $this->email;
            if ($this->password) {
                $user->setPassword($this->password);
            }
            $userExtra->phone = $this->phone;
            $userExtra->address = $this->address;
            $userExtra->nif = $this->nif;
            $userExtra->localsellpoint_id = $this->localsellpoint;

            // Update or reassign role
            $auth = \Yii::$app->authManager;
            $auth->revokeAll($user->id);  // Remove existing roles
            $newRole = $auth->getRole($this->role);
            if (!$newRole) {
                throw new \Exception("Role not found");
            }
            $auth->assign($newRole, $user->id);

            // Save user and userExtra
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

}