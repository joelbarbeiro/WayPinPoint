<?php

namespace common\models;

use backend\models\Localsellpoint;
use backend\models\LocalsellpointUserextra;
use Yii;
use yii\db\Query;

/**
 * This is the model class for table "userextras".
 *
 * @property int $id
 * @property int $user_id
 * @property int $localsellpoint_id
 * @property int $nif
 * @property string $address
 * @property string $phone
 * @property int $supplier
 *
 * @property Localsellpoint $localsellpoint
 * @property LocalsellpointUserextra[] $localsellpointUserextras
 * @property User $user
 */
class UserExtra extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'userextras';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'localsellpoint_id', 'nif', 'address', 'phone'], 'required'],
            [['user_id', 'localsellpoint_id', 'nif', 'supplier'], 'integer'],
            [['address'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],
            [['nif'], 'unique'],
            [['localsellpoint_id'], 'exist', 'skipOnError' => true, 'targetClass' => Localsellpoint::class, 'targetAttribute' => ['localsellpoint_id' => 'id']],
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
            'localsellpoint_id' => 'Localsellpoint ID',
            'nif' => 'Nif',
            'address' => 'Address',
            'phone' => 'Phone',
            'supplier' => 'Supplier',
        ];
    }

    /**
     * Gets query for [[Localsellpoint]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocalsellpoint()
    {
        return $this->hasOne(Localsellpoint::class, ['id' => 'localsellpoint_id']);
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

    public static function getManagersForSupplier($userId): array
    {
        $managerIds = Yii::$app->authManager->getUserIdsByRole('manager');

        return User::find()
            ->select(['id', 'username'])
            ->where(['id' => $managerIds])
            ->andWhere(['id' => (new Query())
                ->select('user_id')
                ->from('userextras')
                ->where(['supplier' => $userId])
            ])
            ->asArray()
            ->all();

    }

    public static function getEmployeesForSupplier($userId): array
    {
        return UserExtra::find()
            ->select(['userextras.id', 'userextras.user_id', 'userextras.supplier', 'user.username'])
            ->innerJoin('user', 'user.id = userextras.user_id')
            ->where(['userextras.supplier' => $userId])
            ->andWhere(['user.status'=> 10])
            ->asArray()
            ->all();
    }


}
