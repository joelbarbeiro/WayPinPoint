<?php

namespace backend\models;

use common\models\User;
use Yii;

/**
 * This is the model class for table "localsellpoint".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $address
 * @property string $name
 *
 * @property LocalsellpointUserextra[] $localsellpointUserextras
 * @property User $user
 * @property Userextras[] $userextras
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
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['address', 'name'], 'required'],
            [['localuserextra']], 'int',
            [['address'], 'string', 'max' => 400],
            [['name'], 'string', 'max' => 100],
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
            'localuserextra' => 'Local User',
            'address' => 'Address',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[LocalsellpointUserextras]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocalsellpointUserextras()
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
        return $this->hasMany(Userextras::class, ['localsellpoint_id' => 'id']);
    }
}
