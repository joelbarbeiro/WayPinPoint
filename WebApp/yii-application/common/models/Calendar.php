<?php

namespace common\models;

/**
 * This is the model class for table "calendar".
 *
 * @property int $id
 * @property int $activities_id
 * @property int $date_id
 * @property int $time_id
 */
class Calendar extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'calendar';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['activities_id', 'date_id', 'time_id'], 'required'],
            [['activities_id', 'date_id', 'time_id'], 'integer'],
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
            'date_id' => 'Date ID',
            'time_id' => 'Time ID',
        ];
    }


}
