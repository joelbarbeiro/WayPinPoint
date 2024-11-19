<?php

namespace frontend\models;

use backend\models\Activities;

/**
 * This is the model class for table "reviews".
 *
 * @property int $id
 * @property int $activity_id
 * @property int $score
 * @property string|null $message
 * @property string|null $created_at
 *
 * @property Activities $activity
 */
class Reviews extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reviews';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['activity_id', 'score'], 'required'],
            [['activity_id', 'score'], 'integer'],
            [['message'], 'string'],
            [['created_at'], 'safe'],
            [['activity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Activity::class, 'targetAttribute' => ['activity_id' => 'id']],
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
            'score' => 'Score',
            'message' => 'Message',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Activity]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActivity()
    {
        return $this->hasOne(Activities::class, ['id' => 'activity_id']);
    }
}