<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "localsellpoint_userextra".
 *
 * @property int $id
 * @property int $localsellpoint_id
 * @property int $userextra_id
 * @property string $role
 *
 * @property Localsellpoint $localsellpoint
 * @property Userextras $userextra
 */
class LocalsellpointUserextra extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'localsellpoint_userextra';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['localsellpoint_id', 'userextra_id', 'role'], 'required'],
            [['localsellpoint_id', 'userextra_id'], 'integer'],
            [['role'], 'string'],
            [['localsellpoint_id'], 'exist', 'skipOnError' => true, 'targetClass' => Localsellpoint::class, 'targetAttribute' => ['localsellpoint_id' => 'id']],
            [['userextra_id'], 'exist', 'skipOnError' => true, 'targetClass' => Userextras::class, 'targetAttribute' => ['userextra_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'localsellpoint_id' => 'Localsellpoint ID',
            'userextra_id' => 'Userextra ID',
            'role' => 'Role',
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
     * Gets query for [[Userextra]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserextra()
    {
        return $this->hasOne(Userextras::class, ['id' => 'userextra_id']);
    }
}
