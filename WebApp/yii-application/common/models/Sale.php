<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "sale".
 *
 * @property int $id
 * @property int $activity_id
 * @property int $buyer
 * @property float $total
 * @property string $purchase_date
 * @property int $seller_id
 * @property int $localsellpoint_id
 * @property int $quantity
 *
 * @property Activity $activity
 * @property User $buyer0
 * @property Invoice[] $invoices
 * @property Userextra $localsellpoint
 * @property User $seller
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
            [['activity_id', 'buyer', 'purchase_date', 'seller_id', 'localsellpoint_id', 'quantity'], 'required'],
            [['activity_id', 'buyer', 'seller_id', 'localsellpoint_id', 'quantity'], 'integer'],
            [['total'], 'number'],
            [['purchase_date'], 'safe'],
            [['activity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Activity::class, 'targetAttribute' => ['activity_id' => 'id']],
            [['buyer'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['buyer' => 'id']],
            [['localsellpoint_id'], 'exist', 'skipOnError' => true, 'targetClass' => Userextra::class, 'targetAttribute' => ['localsellpoint_id' => 'localsellpoint_id']],
            [['seller_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['seller_id' => 'id']],
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
            'buyer' => 'Buyer',
            'total' => 'Total',
            'purchase_date' => 'Purchase Date',
            'seller_id' => 'Seller ID',
            'localsellpoint_id' => 'Localsellpoint ID',
            'quantity' => 'Quantity',
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

    /**
     * Gets query for [[Buyer0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBuyer0()
    {
        return $this->hasOne(User::class, ['id' => 'buyer']);
    }

    /**
     * Gets query for [[Invoices]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInvoices()
    {
        return $this->hasMany(Invoice::class, ['sale_id' => 'id']);
    }

    /**
     * Gets query for [[Localsellpoint]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLocalsellpoint()
    {
        return $this->hasOne(Userextra::class, ['localsellpoint_id' => 'localsellpoint_id']);
    }

    /**
     * Gets query for [[Seller]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSeller()
    {
        return $this->hasOne(User::class, ['id' => 'seller_id']);
    }

    public static function createSale($activityId)
    {
        $activity = Activity::findOne($activityId);
        $userId = Yii::$app->user->id;
        $cart = Cart::find()
            ->where(['user_id' => $userId, 'product_id' => $activityId])
            ->one();
        $model = new Sale();
        $model->seller_id = 1;
        $model->activity_id = $activityId;
        $model->buyer = $userId;
        $model->total = $activity->priceperpax * $cart->quantity;
        $model->localsellpoint_id = 1;
        $model->purchase_date = new Expression('NOW()');
        $model->save();
        return $model;

    }


    public static function getLocalSellPoints()
    {
        return (new Query())
            ->select(['localsellpoint_id'])
            ->from('userextra')
            ->where(['user_id' => Yii::$app->user->id])
            ->scalar(); // Returns a single `localsellpoint_id` value
    }

}
