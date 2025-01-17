<?php

namespace common\models;

use Bluerhinos\phpMQTT;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;
use function PHPUnit\Framework\isEmpty;


/**
 * This is the model class for table "activity".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $photo
 * @property int $maxpax
 * @property float $priceperpax
 * @property string $address
 * @property int $status
 * @property int $user_id
 * @property int $category_id
 *
 * @property Booking[] $booking
 * @property Calendar[] $calendar
 * @property Picture[] $picture
 * @property Sale[] $sale
 * @property Ticket[] $ticket
 */
class Activity extends \yii\db\ActiveRecord
{
    public $photoFile;
    public $hour = [];
    public $date = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'activity';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'photo', 'maxpax', 'priceperpax', 'address', 'category_id'], 'required'],
            [['maxpax'], 'number', 'min' => 1],
            [['priceperpax'], 'number', 'min' => 1],
            [['name'], 'string', 'max' => 200],
            [['description'], 'string', 'max' => 255],
            [['photo'], 'string', 'max' => 250],
            [['address'], 'string', 'max' => 400],
            [['user_id'], 'integer'],
            [['status'], 'integer'],
            [['photoFile'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
            [['date', 'hour'], 'required'],
            [['date'], 'each', 'rule' => ['date', 'format' => 'php:Y-m-d']],
            [['hour'], 'each', 'rule' => ['integer']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class,
                'targetAttribute' => ['category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
            'photo' => 'Photo',
            'maxpax' => 'Maximum Passengers',
            'priceperpax' => 'Price per person',
            'address' => 'Address',
            'user_id' => 'User ID',
            'status' => 'Status',
            'photoFile' => 'Upload Photo',
            'date' => 'Date',
            'hour' => 'Custom Hours',
            'category_id' => 'Category',
        ];
    }

    public function uploadPhoto()
    {
        $uploadBackendPath = $this->checkBackendUploadFolder();
        $uploadFrontendPath = $this->checkFrontendUploadFolder();
        $binaryFile = UploadedFile::getInstance($this, 'photoFile');
        if (!$binaryFile) {
            $binaryFile = UploadedFile::getInstanceByName('photoFile');
        }
        if ($binaryFile) {
            $changeFileName = Yii::$app->security->generateRandomString(16) . '.' . $binaryFile->extension;
            $fileBackendPath = $uploadBackendPath . $changeFileName;
            $fileFrontendPath = $uploadFrontendPath . $changeFileName;

            if ($binaryFile->saveAs($fileBackendPath)) {
                // Copy the file to the frontend directory
                if (!copy($fileBackendPath, $fileFrontendPath)) {
                    Yii::error("Failed to copy file to frontend directory");
                }

                if ($this->photo != null) {
                    //$this->deletePhoto($this->photo);
                }

                $this->photo = $changeFileName;
                return true;
            } else {
                Yii::error("File save failed at: " . $fileBackendPath);
            }
        } else {
            Yii::error("No file uploaded");
        }
        return false;
    }

    public function deletePhoto($fileName)
    {
        $deleteBackendPath = Yii::getAlias('@backend/web/assets/uploads/' . Yii::$app->user->id . '/' . $fileName);
        $deleteFrontendPath = Yii::getAlias('@frontend/web/assets/uploads/' . Yii::$app->user->id . '/' . $fileName);

        if (file_exists($deleteBackendPath)) {
            unlink($deleteBackendPath);

        }
        if (file_exists($deleteFrontendPath)) {
            unlink($deleteFrontendPath);
        }
    }

    public function checkBackendUploadFolder()
    {
        if ($this->user_id == null) {
            $this->user_id = Yii::$app->user->id;
        }
        $uploadPath = Yii::getAlias('@backend/web/img/activity/' . $this->user_id . '/');
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }
        return $uploadPath;
    }

    public function checkFrontendUploadFolder()
    {
        if ($this->user_id == null) {
            $this->user_id = Yii::$app->user->id;
        }
        $uploadPath = Yii::getAlias('@frontend/web/img/activity/' . $this->user_id . '/');
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }
        return $uploadPath;
    }


    public function setCalendar($id, $date, $hour)
    {
        $newDates = [];
        $currentCalendar = Calendar::find()->where(['activity_id' => $id])->all();

        $existingCalendar = [];
        foreach ($currentCalendar as $entry) {
            $existingCalendar[] = [
                'date' => $entry->date->date,
                'hour_id' => (string)$entry->time_id
            ];
        }
        $newEntries = [];
        foreach ($date as $key => $dateValue) {
            $newEntries[] = [
                'date' => $dateValue,
                'hour_id' => $hour[$key],
            ];
        }
        $test = 0;
        foreach ($newEntries as $entry) {
            foreach ($existingCalendar as $calendar) {
                if ($entry['date'] == $calendar['date'] && $entry['hour_id'] == $calendar['hour_id']) {
                    $test = 0;
                    break;
                } else {
                    $test = 1;
                }
            }
            if ($test == 1) {
                $newDates[$entry['date']][] = $entry['hour_id'];
                $test = 0;
            }
        }
        return $newDates;
    }

    public function getCatories()
    {
        $categories = Category::find()
            ->select(['id', 'description'])
            ->asArray()
            ->all();

        return ArrayHelper::map($categories, 'id', 'description');
    }

    public function getCategory()
    {
        return $this->hasOne(Category::class, ['id' => 'category_id']);
    }

    public function getCalendarArray()
    {
        $calendar = [];
        foreach ($this->date as $index => $date) {
            $time = $this->hour[$index] ?? 0;
            $calendar[$date][] = $this->hour[$index] ?? 0;
        }

        return $calendar;
    }

    public function getDateIfExists($date)
    {
            Calendar::find()
                ->joinWith('date')
                ->where(['date.date' => $date])
                ->one()->date ?? 0;
    }

    public function updateStatusActivity()
    {
        $activities = Activity::find()
            ->joinWith('calendar')
            ->Where(['activity.status' => 1])
            ->andWhere(['calendar.status' => 1])
            ->all();

        $currentDay = date('y-m-d');
        $currentHour = date('H:i:s');
        foreach ($activities as $activity) {
            if ($activity->calendar->date->date <= $currentDay && $activity->calendar->time->hour <= $currentHour) {
                $activity->status = 0;
                $activity->calendar->status = 0;
                $activity->save(false);
            }
        }
    }

    public function getSupplierActivities($userId)
    {
        return Activity::find()
            ->joinWith('calendar')
            ->andWhere(['user_id' => $userId])
            ->andWhere(['activity.status' => 1])
            ->andWhere(['calendar.status' => 1])
            ->all();
    }

    public function getActivity($id, $userId)
    {
        return Activity::find()
            ->joinWith('calendar')
            ->where([
                'activity.id' => $id,
                'activity.user_id' => $userId,
                'activity.status' => 1,
                'calendar.status' => 1
            ])
            ->one();
    }

    public static function getActivities()
    {
        return Activity::find()
            ->joinWith('calendar')
            ->where([
                'activity.status' => 1,
                'calendar.status' => 1
            ])
            ->all();
    }

    public static function getActivityview($id)
    {
        return Activity::find()
            ->joinWith('calendar')
            ->where([
                'activity.id' => $id,
                'activity.status' => 1,
                'calendar.status' => 1,
            ])
            ->one();
    }

    public static function createActivity($model)
    {
        $getDateTimes = $model->getCalendarArray();
        if(YII_ENV_TEST) {
            $model->photo = "testFile.jpg";
        } else {
            $model->uploadPhoto();
        }
        var_dump($model);

        if ($model->user_id == null) {
            $model->user_id = Yii::$app->user->id;
        }
        if ($model->validate() && $model->save()) {
            foreach ($getDateTimes as $dateVal => $timeId) {
                $date = new Date();
                $date->date = $dateVal;
                $date->save();
                foreach ($timeId as $time) {
                    $calendarModel = new Calendar();
                    $calendarModel->activity_id = $model->id;
                    $calendarModel->date_id = $date->id;
                    $calendarModel->time_id = $time;
                    $calendarModel->save();
                }
            }
            return true;
        }
        return false;
    }

    public static function updateActivity($model)
    {
        $getDateTimeUpdate = $model->setCalendar($model->id, $model->date, $model->hour);
        $model->uploadPhoto();
        if ($model->validate() && $model->save()) {
            foreach ($getDateTimeUpdate as $dateVal => $timeId) {
                $date = $model->getDateIfExists($dateVal);
                if ($date == null) {
                    $date = new Date();
                    $date->date = $dateVal;
                    $date->save();
                }
                foreach ($timeId as $time) {
                    $calendarModel = new Calendar();
                    $calendarModel->activity_id = $model->id;
                    $calendarModel->date_id = $date->id;
                    $calendarModel->time_id = $time;
                    $calendarModel->save();
                }
            }
            return true;
        }
        return false;
    }

    public function deleteActivity($id)
    {
        $model = $this->getActivityView($id);
        $model->status = 0;

        if ($model->save(false)) {
            return true;
        }
        return false;
    }

    public static function getSupplierActivityNames($userId): array
    {
        $activities =
            Activity::find()
                ->joinWith('calendar')
                ->where(['activity.user_id' => $userId])
                ->andWhere(['activity.status' => '1'])
                ->andWhere(['calendar.status' => '1'])
                ->all();

        return ArrayHelper::map($activities, 'id', 'name');
    }

    public function getTimeList()
    {
        $hoursQuery = Time::find()->select(['id', 'hour'])->asArray()->all();
        return ArrayHelper::map($hoursQuery, 'id', 'hour');
    }

    /**
     * Gets query for [[Bookings]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooking()
    {
        return $this->hasMany(Booking::class, ['activity_id' => 'id']);
    }

    /**
     * Gets query for [[Calendar]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCalendar()
    {
        return $this->hasMany(Calendar::class, ['activity_id' => 'id']);
    }

    /**
     * Gets query for [[Picture]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPictures()
    {
        return $this->hasMany(Picture::class, ['activity_id' => 'id']);
    }

    /**
     * Gets query for [[Sales]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSale()
    {
        return $this->hasMany(Sale::class, ['activity_id' => 'id']);
    }

    /**
     * Gets query for [[Tickets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTicket()
    {
        return $this->hasMany(Ticket::class, ['activity_id' => 'id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::class, ['user_id' => 'id']);
    }

    public function getCalendars()
    {
        return $this->hasMany(Calendar::class, ['activity_id' => 'id']);
    }

    public static function getActivityCount($id)
    {
        $activities = Activity::find()->where(['user_id' => $id])->all();
        return count($activities);
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

        $payLoad = new \stdClass();
        $payLoad->id = $this->id;
        $payLoad->name = $this->name;
        $payLoad->description = $this->description;
        $payLoad->photo = $this->photo;
        $payLoad->maxpax = $this->maxpax;
        $payLoad->priceperpax = $this->priceperpax;
        $payLoad->address = $this->address;
        $payLoad->user_id = $this->user_id;
        $payLoad->status = $this->status;
        $payLoad->category_id = $this->category_id;

        $jsonObject = json_encode($payLoad);
        if ($insert)
            $this->publishToMosquitto("activity/created", $jsonObject);
        else
            $this->publishToMosquitto("activity/updated", $jsonObject);
    }
}