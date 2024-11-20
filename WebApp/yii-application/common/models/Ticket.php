<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "tickets".
 *
 * @property int $id
 * @property int $activity_id
 * @property int $participant
 * @property string $qr
 * @property int $status
 */
class Ticket extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ticket';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['activity_id', 'participant', 'qr'], 'required'],
            [['activity_id', 'participant', 'status'], 'integer'],
            [['qr'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activity_id' => 'activity ID',
            'participant' => 'Participant',
            'qr' => 'Qr',
            'status' => 'Status',
        ];
    }

    public static function createTicket($activityId, $qrCode){
        $model = new Ticket();
        $userId = Yii::$app->user->id;
        $model->activity_id = $activityId;
        $model->participant = $userId;
        $model->qr = $qrCode->getText();
        $model->status = 0;
        $model->save();
        $cenas = 0;
    }
}
