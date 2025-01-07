<?php

namespace common\models;

/**
 * This is the model class for table "tickets".
 *
 * @property int $id
 * @property int $activity_id
 * @property int $participant
 * @property string $qr
 * @property int $status
 * @property int $booking_id
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
            [['booking_id'], 'exist', 'skipOnError' => true, 'targetClass' => Booking::class, 'targetAttribute' => ['booking_id' => 'id']],
            [['qr'], 'string', 'max' => 250],
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
            'activity_id' => 'activity ID',
            'participant' => 'Participant',
            'qr' => 'Qr',
            'status' => 'Status',
            'booking_id' => 'Booking ID',
        ];
    }

    /**
     * Gets query for [[Activity]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActivity()
    {
        return $this->hasOne(Activity::class, ['id' => 'activity_id']);
    }

    public function getBooking()
    {
        return $this->hasOne(Booking::class, ['id' => 'booking_id']);
    }

    /**
     * Gets query for [[Participant0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getParticipant()
    {
        return $this->hasOne(User::class, ['id' => 'participant']);
    }

    public static function createTicket($cart, $qrCode, $bookingId)
    {
        $model = new Ticket();
        $model->activity_id = $cart->product_id;
        $model->participant = $cart->user_id;
        $model->booking_id = $bookingId;
        $model->qr = $qrCode->getText();
        $model->status = 0;
        if ($model->save()) {
            return true;
        } else {
            return false;
        }
    }
}
