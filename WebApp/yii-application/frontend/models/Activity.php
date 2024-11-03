<?php

namespace app\models;

use Yii;

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
 * @property Booking[] $bookings
 * @property Calendar[] $calendars
 * @property Picture[] $pictures
 * @property Sale[] $sales
 * @property Ticket[] $tickets
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
        ];
    }

    /**
     * Gets query for [[Bookings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBookings()
    {
        return $this->hasMany(Booking::class, ['activities_id' => 'id']);
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
        return $this->hasMany(Picture::class, ['activities_id' => 'id']);
    }

    /**
     * Gets query for [[Sales]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSales()
    {
        return $this->hasMany(Sale::class, ['activities_id' => 'id']);
    }

    /**
     * Gets query for [[Tickets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTickets()
    {
        return $this->hasMany(Ticket::class, ['activities_id' => 'id']);
    }
}
