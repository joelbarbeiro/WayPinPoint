<?php

namespace common\models;

use Yii;
use yii\db\Expression;

/**
 * This is the model class for table "sales".
 *
 * @property int $id
 * @property int $activity_id
 * @property int $buyer
 * @property float $total
 * @property string $purchase_date
 */
class Sale extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sale';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['activity_id', 'buyer', 'total', 'purchase_date'], 'required'],
            [['activity_id', 'buyer'], 'integer'],
            [['total'], 'number'],
            [['purchase_date'], 'safe'],
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
            'buyer' => 'Buyer',
            'total' => 'Total',
            'purchase_date' => 'Purchase Date',
        ];
    }
    public function getInvoices()
    {
        return $this->hasMany(Invoice::class, ['sales_id' => 'id']);
    }
    public function getUsers()
    {
        return $this->hasOne(User::class, ['user' => 'buyer']);
    }
    public static function createSale($activityId)
    {
        $activity = Activity::findOne($activityId);
        $userId = Yii::$app->user->id;
        $cart = Cart::find()
            ->where(['user_id' => $userId , 'product_id' => $activityId])
            ->one();
        $model = new Sale();
        $model->activity_id = $activityId;
        $model->buyer = $userId;
        $model->total = $activity->priceperpax * $cart->quantity;
        $model->purchase_date = new Expression('NOW()');
        $model->save();
    }
}
