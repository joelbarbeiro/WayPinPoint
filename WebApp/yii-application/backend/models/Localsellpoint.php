<?php

namespace backend\models;

use common\models\User;

/**
 * This is the model class for table "localsellpoint".
 *
 * @property int $id
 * @property int|null $user_id
 * @property string $address
 * @property string $name
 *
 * @property User $user
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
            [['manager_id'], 'integer'],
            [['address', 'name'], 'required'],
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
            'manager_id' => 'Manager ID',
            'address' => 'Address',
            'name' => 'Name',
        ];
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

    public function getManager()
    {
        return $this->hasOne(User::class, ['id' => 'manager_id']);
    }

}
