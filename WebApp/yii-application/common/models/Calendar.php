<?php

namespace common\models;

use common\models\Bookings;

/**
 * This is the model class for table "calendar".
 *
 * @property int $id
 * @property int $activities_id
 * @property int $date_id
 * @property int $time_id
 * @property int $status
 *
 * @property Activity $activity
 * @property Bookings[] $bookings
 * @property Dates $date
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
            [['activities_id', 'date_id', 'time_id'], 'required'],
            [['activities_id', 'date_id', 'time_id'], 'integer'],
            [['activities_id'], 'exist', 'skipOnError' => true, 'targetClass' => Activity::class, 'targetAttribute' => ['activities_id' => 'id']],
            [['date_id'], 'exist', 'skipOnError' => true, 'targetClass' => Dates::class, 'targetAttribute' => ['date_id' => 'id']],
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
            'activities_id' => 'Activities ID',
            'date_id' => 'Date ID',
            'time_id' => 'Time ID',
            'status' => 'Status',
        ];
    }


    /**
     * Gets query for [[Activities]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActivitiy()
    {
        return $this->hasOne(Activity::class, ['id' => 'activities_id']);
    }

    /**
     * Gets query for [[Bookings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Bookings::class, ['calendar_id' => 'id']);
    }

    /**
     * Gets query for [[Date]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDate()
    {
        return $this->hasOne(Dates::class, ['id' => 'date_id']);
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
