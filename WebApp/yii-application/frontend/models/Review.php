<?php

namespace frontend\models;

use common\models\Activity;
use common\models\User;
use Bluerhinos\phpMQTT;

/**
 * This is the model class for table "reviews".
 *
 * @property int $id
 * @property int $activity_id
 * @property int $score
 * @property string|null $message
 * @property string|null $created_at
 *
 * @property Activity $activity
 */
class Review extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'review';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['activity_id', 'score'], 'required'],
            [['activity_id', 'score'], 'integer'],
            [['message'], 'string'],
            [['created_at'], 'safe'],
            [['activity_id'], 'exist', 'skipOnError' => true, 'targetClass' => Activity::class, 'targetAttribute' => ['activity_id' => 'id']],
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
            'score' => 'Score',
            'message' => 'Message',
            'created_at' => 'Created At',
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

    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function publishToMosquitto($topic,$message)
    {
        $server = "127.0.0.1";
        $port = 1883;
        $username = ""; // set your username
        $password = ""; // set your password
        $client_id = "phpMQTT-publisher"; // unique!
        $mqtt = new phpMQTT($server, $port, $client_id);
        if ($mqtt->connect(true, NULL, $username, $password)) {
            $mqtt->publish($topic, $message, 0);
            $mqtt->close();
        } else {
            file_put_contents("debug.output", "Time out!");
        }
    }
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $user = User::findOne(['id' => $this->user_id]);

        $payLoad = new \stdClass();
        $payLoad->id = $this->id;
        $payLoad->user_id = $this->user_id;
        $payLoad->activity_id = $this->activity_id;
        $payLoad->score = $this->score;
        $payLoad->message = $this->message;
        $payLoad->created_at = $this->created_at;
        $payLoad->creator = $user->username;

        $jsonObject = json_encode($payLoad);
        if ($insert)
            $this->publishToMosquitto("review/created", $jsonObject);
        else
            $this->publishToMosquitto("review/updated", $jsonObject);
    }
}