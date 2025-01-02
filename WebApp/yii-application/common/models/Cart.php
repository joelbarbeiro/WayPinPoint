<?php

namespace common\models;

use Da\QrCode\QrCode;
use Dompdf\Dompdf;

/**
 * This is the model class for table "cart".
 *
 * @property int $user_id
 * @property int $product_id
 * @property int $quantity
 * @property int $id
 * @property int $calendar_id
 *
 * @property Calendar $calendar
 * @property Activity $product
 * @property User $user
 */
class Cart extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cart';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'product_id', 'quantity', 'calendar_id'], 'required'],
            [['user_id', 'product_id', 'quantity', 'calendar_id'], 'integer'],
            [['calendar_id'], 'exist', 'skipOnError' => true, 'targetClass' => Calendar::class, 'targetAttribute' => ['calendar_id' => 'id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Activity::class, 'targetAttribute' => ['product_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'product_id' => 'Product ID',
            'quantity' => 'Number of tickets',
            'id' => 'ID',
            'status' => 'Status',
            'calendar_id' => 'Date of Activity',
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActivity()
    {
        return $this->hasOne(Activity::class, ['id' => 'product_id']);
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

    public function getCalendar()
    {
        return $this->hasOne(Calendar::class, ['id' => 'calendar_id']);
    }

    public static function generatePdf($content): ?string
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($content);
        $dompdf->setPaper('A4');
        $dompdf->render();
        return $dompdf->output();
    }

    public static function generateQrCode($user, $activity)
    {
        $qrCodeData = "User: $user->username, Activity: $activity->description, Price: $activity->priceperpax";
        $qrCode = (new QrCode($qrCodeData))
            ->setSize(250)
            ->setMargin(5)
            ->setBackgroundColor(51, 153, 255);
        return $qrCode;
    }

}
