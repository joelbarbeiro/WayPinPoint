<?php

namespace common\models;

/**
 * This is the model class for table "invoice".
 *
 * @property int $id
 * @property int $user_id
 * @property int $sale_id
 * @property int $booking_id
 *
 * @property Booking $booking
 * @property Sale $sale
 * @property User $user
 */
class Invoice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoice';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'sale_id', 'booking_id'], 'required'],
            [['user_id', 'sale_id', 'booking_id'], 'integer'],
            [['booking_id'], 'exist', 'skipOnError' => true, 'targetClass' => Booking::class, 'targetAttribute' => ['booking_id' => 'id']],
            [['sale_id'], 'exist', 'skipOnError' => true, 'targetClass' => Sale::class, 'targetAttribute' => ['sale_id' => 'id']],
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
            'user_id' => 'User ID',
            'sale_id' => 'Sale ID',
            'booking_id' => 'Booking ID',
        ];
    }

    /**
     * Gets query for [[Booking]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooking()
    {
        return $this->hasOne(Booking::class, ['id' => 'booking_id']);
    }

    /**
     * Gets query for [[Sale]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSale()
    {
        return $this->hasOne(Sale::class, ['id' => 'sale_id']);
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


    public static function createInvoice($cart, $saleId, $bookingId)
    {
        $model = new Invoice();
        $model->user_id = $cart->user_id;
        $model->sale_id = $saleId;
        $model->booking_id = $bookingId;
        if ($model->save()) {
            return true;
        } else {
            return false;
        }
    }

    public static function getInvoiceCount()
    {
        return static::find()->count();
    }

}
