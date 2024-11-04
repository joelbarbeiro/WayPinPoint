<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "sales".
 *
 * @property int $id
 * @property int $activities_id
 * @property int $buyer
 * @property float $total
 *
 * @property Activities $activities
 * @property User $buyer0
 * @property Invoices[] $invoices
 */
class Sales extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sales';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['activities_id', 'buyer', 'total'], 'required'],
            [['activities_id', 'buyer'], 'integer'],
            [['total'], 'number'],
            [['activities_id'], 'exist', 'skipOnError' => true, 'targetClass' => Activities::class, 'targetAttribute' => ['activities_id' => 'id']],
            [['buyer'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['buyer' => 'id']],
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
            'buyer' => 'Buyer',
            'total' => 'Total',
        ];
    }

    /**
     * Gets query for [[Activities]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActivities()
    {
        return $this->hasOne(Activities::class, ['id' => 'activities_id']);
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
        return $this->hasMany(Invoices::class, ['sales_id' => 'id']);
    }
}
