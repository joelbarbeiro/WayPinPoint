<?php

namespace common\models;

use backend\models\Activities;

/**
 * This is the model class for table "cart".
 *
 * @property int $user_id
 * @property int $product_id
 * @property int $quantity
 * @property int $id
 *
 * @property Activities $product
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
            [['user_id', 'product_id', 'quantity'], 'required'],
            [['user_id', 'product_id', 'quantity'], 'integer'],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Activities::class, 'targetAttribute' => ['product_id' => 'id']],
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
            'quantity' => 'Quantity',
            'id' => 'ID',
        ];
    }

    /**
     * Gets query for [[Product]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActivity()
    {
        return $this->hasOne(Activities::class, ['id' => 'product_id']);
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
}
