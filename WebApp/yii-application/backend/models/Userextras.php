<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "userextras".
 *
 * @property int $id
 * @property int $user
 * @property string $phone
 * @property int $supplier
 *
 * @property User $user0
 */
class Userextras extends \yii\db\ActiveRecord
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
            [['user', 'phone'], 'required'],
            [['user', 'supplier'], 'integer'],
            [['phone'], 'string', 'max' => 20],
            [['user'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user' => 'User',
            'phone' => 'Phone',
            'supplier' => 'Supplier',
        ];
    }

    /**
     * Gets query for [[User0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser0()
    {
        return $this->hasOne(User::class, ['id' => 'user']);
    }
}
