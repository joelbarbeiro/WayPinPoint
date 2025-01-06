<?php

namespace common\models;

/**
 * This is the model class for table "booking".
 *
 * @property int $id
 * @property int $activity_id
 * @property int $calendar_id
 * @property int $user_id
 * @property int $numberpax
 *
 * @property Activity $activity
 * @property Calendar $calendar
 * @property Invoice[] $invoices
 * @property User $user
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
            [['activity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Activity::class, 'targetAttribute' => ['activity_id' => 'id']],
            [['calendar_id'], 'exist', 'skipOnError' => true, 'targetClass' => Calendar::class, 'targetAttribute' => ['calendar_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'calendar_id' => 'Calendar ID',
            'user_id' => 'User ID',
            'numberpax' => 'Numberpax',
        ];
    }

    /**
     * Gets query for [[Activity]].
     *
     * @return string
     */
    public function getActivity()
    {
        return $this->hasOne(Activity::class, ['id' => 'activity_id']);
    }

    /**
     * Gets query for [[Calendar]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCalendar()
    {
        return $this->hasOne(Calendar::class, ['id' => 'calendar_id']);
    }

    /**
     * Gets query for [[Invoices]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInvoices()
    {
        return $this->hasMany(Invoice::class, ['booking_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function createBooking($cart)
    {
        $model = new Booking();
        $model->activity_id = $cart->product_id;
        $model->user_id = $cart->user_id;
        $model->numberpax = $cart->quantity;
        $model->calendar_id = $cart->calendar_id;
        if ($model->save()) {
            return $model->id;
        } else {
            return false;
        }
    }

    public static function getBookingsCount($id)
    {
        $bookingsCount = Booking::find()->where(['user_id' => $id])->all();
        return count($bookingsCount);
    }

    public static function getBookingsForActivityCount($id)
    {
        $bookingsCount = Booking::find()->where(['activity_id' => $id])->all();
        return count($bookingsCount);
    }

    public static function getTotalTicketsByActivity($id)
    {
        $sum = 0;
        $tickets = Booking::find()->where(['activity_id' => $id])->all();
        foreach ($tickets as $ticket) {
            $sum = $sum + $ticket->numberpax;
        }
        return $sum;
    }

}
