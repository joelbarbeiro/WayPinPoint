<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;


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
            [['name', 'description', 'photo', 'maxpax', 'priceperpax', 'address'], 'required'],
            [['maxpax'], 'integer'],
            [['priceperpax'], 'number'],
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
            'maxpax' => 'Maxpax',
            'priceperpax' => 'Priceperpax',
            'address' => 'Address',
            'user_id' => 'User ID',
            'status' => 'Status',
            'photoFile' => 'Upload Photo',
            'date' => 'Date',
            'hour' => 'Custom Hours',
        ];
    }

    public function uploadPhoto()
    {
        $uploadBackendPath = $this->checkBackendUploadFolder();
        $uploadFrontendPath = $this->checkFrontendUploadFolder();

        $binaryFile = UploadedFile::getInstance($this, 'photoFile');
        if ($binaryFile) {
            $changeFileName = Yii::$app->security->generateRandomString(16) . '.' . $binaryFile->extension;
            $fileBackendPath = $uploadBackendPath . $changeFileName;
            $fileFrontendPath = $uploadFrontendPath . $changeFileName;

            if ($binaryFile->saveAs($fileBackendPath)) {
                // Copy the file to the frontend directory
                if (!copy($fileBackendPath, $fileFrontendPath)) {
                    Yii::error("Failed to copy file to frontend directory");
                }

                $this->deletePhoto($this->photo);

                $this->photo = $changeFileName;
            } else {
                Yii::error("File save failed at: " . $fileBackendPath);
            }
        } else {
            Yii::error("No file uploaded");
        }
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
        $uploadPath = Yii::getAlias('@backend/web/img/activity/' . Yii::$app->user->id . '/');
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }
        return $uploadPath;
    }

    public function checkFrontendUploadFolder()
    {
        $uploadPath = Yii::getAlias('@frontend/web/img/activity/' . Yii::$app->user->id . '/');
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }
        return $uploadPath;
    }

    public function setCalendar($id, $date, $hour)
    {
        $newCalendar = new Calendar();
        $currentCalendar = Calendar::find()->where(['activity_id' => $id])->all();
        foreach ($currentCalendar as $entry) {
            $existingCalendar[] = [
                'date' => $entry->date->date,
                'hour_id' => $entry->time_id
            ];
        }
        foreach ($date as $key => $dateValue)
        {
            $newEntries[] = [
                'date' => $dateValue,
                'hour_id' => $hour[$key],
            ];
        }


        dd($existingCalendar, $newEntries);
        /*
        echo count($date) . '</br>';
        foreach ($currentCalendar as $calendar) {
            for ($i = 0; $i < count($date); $i++) {
                if (($calendar->date->date != $date[$i] && $calendar->time_id != $hour[$i]) && $calendar->status == 1) {
                    echo "Update </br>";
                    echo $calendar->date->date . " - " . $hour[$i] . "</br>";
                    $opcao = 1;
                } else {
                    $opcao = 0;
                }
            }
            if ($opcao == 1) {
                $modelDate = new Date();
                $modelDate->date = $date[$i];
                $modelDate->save();
                $newCalendar->activity_id = $id;
                $newCalendar->time_id = $hour[$i];
                $newCalendar->date_id = $modelDate->id;
                $newCalendar->save();
                $opcao = 0;
            }
        }*/
    }

    public function getCalendarArray($id)
    {
        $calendar = [];
        $availableCalendar = Calendar::find()->where(['activity_id' => $id])->all();
        $existingCombinations = [];

        foreach ($availableCalendar as $dateAvailable) {
            $existingCombinations[$dateAvailable->date->date][] = $dateAvailable->time_id;
        }
        foreach ($this->date as $index => $date) {
            $time = $this->hour[$index] ?? 0;
            if (isset($existingCombinations[$date]) && in_array($time, $existingCombinations[$date])) {
                continue;
            }
            if (!isset($calendar[$date])) {
                $calendar[$date] = [];
            }
            $calendar[$date][] = $this->hour[$index] ?? 0;
        }

        return $calendar;
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
                'calendar.status' => 1
            ])
            ->one();
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
}