<?php

namespace backend\models;

use common\models\User;
use common\models\UserExtra;
use yii\db\Query;

/**
 * This is the model class for table "localsellpoint".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $address
 * @property string $name
 *
 * @property LocalsellpointUserextra[] $localsellpointUserextra
 * @property User $user
 * @property UserExtra[] $userextra
 */
class Localsellpoint extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'localsellpoint';
    }

    /**
     * {@inheritdoc}
     */

    public $localuserextra;

    public $assignedEmployees = [];
    public $assignedManager;


    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['address', 'name'], 'required'],
            [['address'], 'string', 'max' => 400],
            [['name'], 'string', 'max' => 100],
            [['localuserextra'], 'integer'],
            [['assignedManager'], 'safe'],
            [['assignedEmployees'], 'safe'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'address' => 'Address',
            'name' => 'Name',
            'assignedManager' => 'Assigned Manager',
            'assignedEmployees' => 'Assigned Employees',
            'localuserextra' => 'Local User',
        ];
    }

    /**
     * Gets query for [[LocalsellpointUserextras]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocalsellpointUserextra()
    {
        return $this->hasMany(LocalsellpointUserextra::class, ['localsellpoint_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Gets query for [[Userextras]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserextras()
    {
        return $this->hasMany(UserExtra::class, ['id' => 'userextra_id'])
            ->via('localsellpointUserextra');
    }

    public static function getLocalStoreForSupplier($userId): array
    {
        return Localsellpoint::find()
            ->select(['id', 'name'])
            ->where(['user_id' => $userId])
            ->asArray()
            ->all();
    }

    public static function getEmployeesForLocalStore($id, $userId): array
    {
        $localStore = Localsellpoint::findOne($id);

        return User::find()
            ->select(['id', 'username'])
            ->where(['status' => 10])
            ->andWhere(['id' => (new Query())
                ->select('user_id')
                ->from('userextra')
                ->where(['supplier' => $userId])
                ->andWhere(['localsellpoint_id' => $localStore->id])
            ])
            ->asArray()
            ->all();
    }

    public static function getLocalsellpointCount($id)
    {
        $localshops = Localsellpoint::find()->where(['user_id' => $id])->all();
        return count($localshops);
    }

}
