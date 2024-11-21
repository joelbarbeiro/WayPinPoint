<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "invoices".
 *
 * @property int $id
 * @property int $user
 * @property int $sale_id
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
            [['user', 'sale_id'], 'required'],
            [['user', 'sale_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user' => 'User',
            'sale_id' => 'Sales ID',
        ];
    }

    public function getSale()
    {
        return $this->hasOne(Sale::class, ['id' => 'sale_id']);
    }

    public function getUsers()
    {
        return $this->hasOne(User::class, ['user' => 'id']);
    }

    public static function createInvoice($activityId)
    {
        $userId = Yii::$app->user->id;
        $sale = Sale::find()
            ->where(['buyer' => $userId, 'activity_id' => $activityId])
            ->one();
        $model = new Invoice();
        $model->user = $userId;
        $model->sale_id = $sale->id;
        $sale->save();
        $model->save();
    }

}
