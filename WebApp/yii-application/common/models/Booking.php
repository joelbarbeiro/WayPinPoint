<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "bookings".
 *
 * @property int $id
 * @property int $activities_id
 * @property int $calendar_id
 * @property int $user_id
 * @property int $numberpax
 */
class Booking extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'bookings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['activities_id', 'calendar_id', 'user_id', 'numberpax'], 'required'],
            [['activities_id', 'calendar_id', 'user_id', 'numberpax'], 'integer'],
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
            'calendar_id' => 'Calendar ID',
            'user_id' => 'User ID',
            'numberpax' => 'Numberpax',
        ];
    }
    public static function createBooking($activityId)
    {
        $userId = Yii::$app->user->id;
        $cart = Cart::find()
            ->where(['user_id' => $userId , 'product_id' => $activityId])
            ->one();
        $model = new Booking();
        $model->activities_id = $activityId;
        $userId = Yii::$app->user->id;
        $model->user_id = $userId;
        $model->numberpax = $cart->quantity;
        $model->calendar_id = 1;
        $model->save();
    }
}
