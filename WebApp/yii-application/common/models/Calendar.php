<?php

namespace common\models;

use common\models\Booking;

/**
 * This is the model class for table "calendar".
 *
 * @property int $id
 * @property int $activity_id
 * @property int $date_id
 * @property int $time_id
 * @property int $status
 *
 * @property Activity $activity
 * @property Booking[] $booking
 * @property Date $date
 * @property Time $time
 *
 */
class Calendar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'calendar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['activity_id', 'date_id', 'time_id'], 'required'],
            [['activity_id', 'date_id', 'time_id'], 'integer'],
            [['activity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Activity::class, 'targetAttribute' => ['activity_id' => 'id']],
            [['date_id'], 'exist', 'skipOnError' => true, 'targetClass' => Date::class, 'targetAttribute' => ['date_id' => 'id']],
            [['time_id'], 'exist', 'skipOnError' => true, 'targetClass' => Time::class, 'targetAttribute' => ['time_id' => 'id']],
            [['status'], 'integer'],
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
            'date_id' => 'Date ID',
            'time_id' => 'Time ID',
            'status' => 'Status',
        ];
    }


    /**
     * Gets query for [[activity]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getactivity()
    {
        return $this->hasOne(Activity::class, ['id' => 'activity_id']);
    }

    /**
     * Gets query for [[Bookings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Booking::class, ['calendar_id' => 'id']);
    }

    /**
     * Gets query for [[Date]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDate()
    {
        return $this->hasOne(Date::class, ['id' => 'date_id']);
    }

    /**
     * Gets query for [[Time]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTime()
    {
        return $this->hasOne(Time::class, ['id' => 'time_id']);
    }
}
