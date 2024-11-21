<?php

namespace common\models;

/**
 * This is the model class for table "pictures".
 *
 * @property int $id
 * @property int $activity_id
 * @property int $user_id
 * @property string $reviews
 * @property string $uri
 *
 * @property Activity $activity
 * @property User $user
 */
class Picture extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'picture';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['activity_id', 'user_id', 'reviews', 'uri'], 'required'],
            [['activity_id', 'user_id'], 'integer'],
            [['reviews'], 'string', 'max' => 500],
            [['uri'], 'string', 'max' => 250],
            [['activity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Activity::class, 'targetAttribute' => ['activity_id' => 'id']],
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
            'activity_id' => 'Activity ID',
            'user_id' => 'User ID',
            'reviews' => 'Review',
            'uri' => 'Uri',
        ];
    }

    /**
     * Gets query for [[activity]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getactivity()
    {
        return $this->hasOne(activity::class, ['id' => 'activity_id']);
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