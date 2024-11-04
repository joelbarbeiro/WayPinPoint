<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "pictures".
 *
 * @property int $id
 * @property int $activities_id
 * @property int $user_id
 * @property string $reviews
 * @property string $uri
 *
 * @property Activities $activities
 * @property User $user
 */
class Pictures extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'pictures';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['activities_id', 'user_id', 'reviews', 'uri'], 'required'],
            [['activities_id', 'user_id'], 'integer'],
            [['reviews'], 'string', 'max' => 500],
            [['uri'], 'string', 'max' => 250],
            [['activities_id'], 'exist', 'skipOnError' => true, 'targetClass' => Activities::class, 'targetAttribute' => ['activities_id' => 'id']],
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
            'activities_id' => 'Activities ID',
            'user_id' => 'User ID',
            'reviews' => 'Reviews',
            'uri' => 'Uri',
        ];
    }

    /**
     * Gets query for [[Activities]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActivities()
    {
        return $this->hasOne(Activities::class, ['id' => 'activities_id']);
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
}
