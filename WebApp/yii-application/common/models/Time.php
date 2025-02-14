<?php

namespace common\models;

/**
 * This is the model class for table "times".
 *
 * @property int $id
 * @property string $hour
 *
 * @property Calendar[] $calendars
 */
class Time extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'time';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hour'], 'required'],
            [['hour'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'hour' => 'Hour',
        ];
    }
    public static function getTimes()
    {
        return Time::find()->all();
    }
    /**
     * Gets query for [[Calendars]].
     *
     * @return \yii\db\ActiveQuery
     */

    public function getCalendars()
    {
        return $this->hasMany(Calendar::class, ['time_id' => 'id']);
    }

}
