<?php

namespace common\models;

use frontend\models\Picture;

use backend\models\Bookings;
use backend\models\Calendar;
use backend\models\Pictures;
use backend\models\Sales;
use backend\models\Tickets;
use common\models\User;
use Yii;
use yii\web\UploadedFile;


/**
 * This is the model class for table "activities".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $photo
 * @property int $maxpax
 * @property float $priceperpax
 * @property string $address
 *
 * @property Bookings[] $bookings
 * @property Calendar[] $calendars
 * @property Pictures[] $pictures
 * @property Sales[] $sales
 * @property Tickets[] $tickets
 */
class Activity extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activities';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'photo', 'maxpax', 'priceperpax', 'address'], 'required'],
            [['maxpax'], 'integer'],
            [['priceperpax'], 'number'],
            [['name'], 'string', 'max' => 200],
            [['description'], 'string', 'max' => 255],
            [['photo'], 'string', 'max' => 250],
            [['address'], 'string', 'max' => 400],
            [['user_id'], 'integer'],
            [['status'], 'integer'],
            [['photoFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            [['dates', 'hours'], 'required'],
            [['dates'], 'each', 'rule' => ['date', 'format' => 'php:Y-m-d']],
            [['hours'], 'each', 'rule' => ['integer']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'photo' => 'Photo',
            'maxpax' => 'Maxpax',
            'priceperpax' => 'Priceperpax',
            'address' => 'Address',
            'user_id' => 'User ID',
            'status' => 'Status',
            'photoFile' => 'Upload Photo',
            'dates' => 'Dates',
            'hours' => 'Custom Hours',
        ];
    }

    public function getCalendarArray()
    {
        // Create the array of date => array of hours
        $calendar = [];
        foreach ($this->dates as $index => $date) {
            if (!isset($calendar[$date])) {
                $calendar[$date] = [];
            }
            $calendar[$date][] = $this->hours[$index] ?? 0;
        }

        return $calendar;
    }

    /**
     * Gets query for [[Bookings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Bookings::class, ['activities_id' => 'id']);
    }

    /**
     * Gets query for [[Calendars]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCalendars()
    {
        return $this->hasMany(Calendar::class, ['activities_id' => 'id']);
    }

    /**
     * Gets query for [[Pictures]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPictures()
    {
        return $this->hasMany(Pictures::class, ['activities_id' => 'id']);
    }

    /**
     * Gets query for [[Sales]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSales()
    {
        return $this->hasMany(Sales::class, ['activities_id' => 'id']);
    }

    /**
     * Gets query for [[Tickets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTickets()
    {
        return $this->hasMany(Tickets::class, ['activities_id' => 'id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['user_id' => 'id']);
    }
}