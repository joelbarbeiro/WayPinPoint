<?php

namespace backend\models;

use common\models\Activities;

/**
 * This is the model class for table "tickets".
 *
 * @property int $id
 * @property int $activities_id
 * @property int $participant
 * @property string $qr
 * @property int $status
 *
 * @property Activities $activities
 * @property User $participant0
 */
class Tickets extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tickets';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['activities_id', 'participant', 'qr'], 'required'],
            [['activities_id', 'participant', 'status'], 'integer'],
            [['qr'], 'string', 'max' => 250],
            [['activities_id'], 'exist', 'skipOnError' => true, 'targetClass' => Activities::class, 'targetAttribute' => ['activities_id' => 'id']],
            [['participant'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['participant' => 'id']],
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
            'participant' => 'Participant',
            'qr' => 'Qr',
            'status' => 'Status',
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
     * Gets query for [[Participant0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParticipant0()
    {
        return $this->hasOne(User::class, ['id' => 'participant']);
    }
}
