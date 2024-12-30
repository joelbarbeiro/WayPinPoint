<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\web\ServerErrorHttpException;

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
    public $calendar_id;

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
            [['activity_id', 'buyer', 'seller_id', 'localsellpoint_id', 'quantity', 'calendar_id'], 'integer'],
            [['total'], 'number'],
            [['purchase_date'], 'safe'],
            [['activity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Activity::class, 'targetAttribute' => ['activity_id' => 'id']],
            [['buyer'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['buyer' => 'id']],
            [['localsellpoint_id'], 'exist', 'skipOnError' => true, 'targetClass' => Userextra::class, 'targetAttribute' => ['localsellpoint_id' => 'localsellpoint_id']],
            [['seller_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['seller_id' => 'id']],
            [['calendar_id'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'activity_id' => 'Activity',
            'buyer' => 'Buyer',
            'total' => 'Total',
            'purchase_date' => 'Purchase Date',
            'seller_id' => 'Seller',
            'localsellpoint_id' => 'Localsellpoint',
            'quantity' => 'Quantity',
            'calendar_id' => 'Calendar',
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

    public function getSellerExtra()
    {
        return $this->hasOne(Userextra::class, ['user_id' => 'seller_id']);
    }

    public static function createSale($cart)
    {
        $model = new Sale();
        $model->seller_id = 1;
        $model->localsellpoint_id = 1;
        $model->activity_id = $cart->product_id;
        $model->buyer = $cart->user_id;
        $model->quantity = $cart->quantity;
        $model->total = $cart->activity->priceperpax * $cart->quantity;
        $model->purchase_date = new Expression('NOW()');
        if ($model->save()) {
            return $model->id;
        } else {
            return false;
        }
    }

    public static function createBooking($activity, $buyer, $calendar_id, $quantity)
    {
        $booking = new Booking();
        $booking->activity_id = $activity->id;
        $booking->calendar_id = $calendar_id;
        $booking->user_id = $buyer;
        $booking->numberpax = $quantity;
        if (!$booking->save()) {
            Yii::error('Failed to create booking: ' . json_encode($booking->errors));
            throw new ServerErrorHttpException('Failed to create booking.');
        }
    }

    public static function getAllClients(): array
    {
        $clients = Yii::$app->authManager->getUserIdsByRole('client');
        return User::find()
            ->select(['id', 'username'])
            ->where(['id' => $clients])
            ->asArray()
            ->all();
    }

    public static function getSupplierSales($id)
    {
        return Sale::find()
            ->joinWith('activity')
            ->where([
                'activity.user_id' => $id,
            ])
            ->all();
    }

    public static function getSalesSellerCount($id)
    {
        $salesSellerCount = Sale::find()->where(['seller_id' => $id])->all();
        return count($salesSellerCount);
    }

    public static function getSalesShopCount($id)
    {
        $salesShopCount = Sale::find()->where(['localsellpoint_id' => $id])->all();
        return count($salesShopCount);
    }

    public static function getTotalSales()
    {
        $total = Sale::find()->select(['total'])->all();
        $totalInvoiced = 0;
        foreach ($total as $sale) {
            $totalInvoiced += $sale->total;
        }
        return $totalInvoiced;
    }

    public static function getSalesForShop($id)
    {
        $total = Sale::find()->where(['localsellpoint_id' => $id])->select(['total'])->all();
        $totalInvoiced = 0;
        foreach ($total as $sale) {
            $totalInvoiced += $sale->total;
        }
        return $totalInvoiced;
    }

    public static function getSalesTotalForDay()
    {
        $today = date('Y-m-d');

        $sales = Sale::find()
            ->where(['DATE(purchase_date)' => $today])
            ->select(['total'])
            ->all();

        $totalInvoiced = 0;
        foreach ($sales as $sale) {
            $totalInvoiced += $sale->total;
        }
        return $totalInvoiced;
    }

}
