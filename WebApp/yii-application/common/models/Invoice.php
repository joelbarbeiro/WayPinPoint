<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "invoices".
 *
 * @property int $id
 * @property int $user
 * @property int $sales_id
 */
class Invoice extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'invoices';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user', 'sales_id'], 'required'],
            [['user', 'sales_id'], 'integer'],
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
            'sales_id' => 'Sales ID',
        ];
    }
    public function getSales()
    {
        return $this->hasOne(Sale::class, ['id' => 'sales_id']);
    }
    public function getUsers()
    {
        return $this->hasOne(User::class, ['user' => 'id']);
    }
    public static function createInvoice($activityId)
    {
        $userId = Yii::$app->user->id;
        $sale = Sale::find()
            ->where(['buyer' => $userId , 'activities_id' => $activityId])
            ->one();
        $model = new Invoice();
        $model->user = $userId;
        $model->sales_id = $sale->id;
        $sale->save();
        $model->save();
    }

}
