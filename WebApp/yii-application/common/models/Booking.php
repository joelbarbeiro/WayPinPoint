<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "bookings".
 *
 * @property int $id
 * @property int $activity_id
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
        return 'booking';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['activity_id', 'calendar_id', 'user_id', 'numberpax'], 'required'],
            [['activity_id', 'calendar_id', 'user_id', 'numberpax'], 'integer'],
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
        $model->activity_id = $activityId;
        $userId = Yii::$app->user->id;
        $model->user_id = $userId;
        $model->numberpax = $cart->quantity;
        $model->calendar_id = 1;
        $model->save();
    }
}
