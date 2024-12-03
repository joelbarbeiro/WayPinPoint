<?php

namespace common\models;

use backend\models\Localsellpoint;
use backend\models\LocalsellpointUserextra;
use Yii;
use yii\db\Query;
use yii\web\UploadedFile;

/**
 * This is the model class for table "userextra".
 *
 * @property int $id
 * @property int $user_id
 * @property int $localsellpoint_id
 * @property int $nif
 * @property string $address
 * @property string $phone
 * @property string $photo
 * @property string $photoFile
 * @property int $supplier
 *
 * @property Localsellpoint $localsellpoint
 * @property LocalsellpointUserextra[] $localsellpointuserextra
 * @property User $user
 */
class UserExtra extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'userextra';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'nif', 'address', 'phone'], 'required'],
            [['user_id', 'localsellpoint_id', 'nif', 'supplier'], 'integer'],
            [['address'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 20],
            [['nif'], 'unique'],
            [['localsellpoint_id'], 'exist', 'skipOnError' => true, 'targetClass' => Localsellpoint::class, 'targetAttribute' => ['localsellpoint_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['photo'], 'string', 'max' => 250],
            ['status', 'default', 'value' => 1],
            ['status', 'in', 'range' => [0, 1]],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'localsellpoint_id' => 'Localsellpoint ID',
            'nif' => 'Nif',
            'address' => 'Address',
            'phone' => 'Phone',
            'photoFile' => 'Upload Photo',
            'photo' => 'Photo',
            'supplier' => 'Supplier',
        ];
    }

    /**
     * Gets query for [[Localsellpoint]].
     *
     * @return \yii\db\ActiveQuery
     */

    public function uploadUserPhoto($signupForm)
    {
        $uploadBackendPath = $this->checkBackendUploadFolder();
        $uploadFrontendPath = $this->checkFrontendUploadFolder();

        $binaryFile = UploadedFile::getInstance($signupForm, 'photoFile');
        if ($binaryFile) {
            $changeFileName = Yii::$app->security->generateRandomString(16) . '.' . $binaryFile->extension;
            $fileBackendPath = $uploadBackendPath . $changeFileName;
            $fileFrontendPath = $uploadFrontendPath . $changeFileName;

            if ($binaryFile->saveAs($fileBackendPath)) {
                // Copy the file to the frontend directory
                if (!copy($fileBackendPath, $fileFrontendPath)) {
                    Yii::error("Failed to copy file to frontend directory");
                }

                $this->photo = $changeFileName;
            } else {
                Yii::error("File save failed at: " . $fileBackendPath);
            }
        } else {
            Yii::error("No file uploaded");
        }
    }

    public function checkBackendUploadFolder()
    {
        $uploadPath = Yii::getAlias('@backend/web/img/user/' . $this->user->id . '/');
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }
        return $uploadPath;
    }

    public function checkFrontendUploadFolder()
    {
        $uploadPath = Yii::getAlias('@frontend/web/img/user/' . $this->user->id . '/');
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }
        return $uploadPath;
    }

    public function getLocalsellpoint()
    {
        return $this->hasOne(Localsellpoint::class, ['id' => 'localsellpoint_id']);
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

    public static function getManagersForSupplier($userId): array
    {
        $managerIds = Yii::$app->authManager->getUserIdsByRole('manager');

        return User::find()
            ->select(['id', 'username'])
            ->where(['id' => $managerIds])
            ->andWhere(['id' => (new Query())
                ->select('user_id')
                ->from('userextra')
                ->where(['supplier' => $userId])
            ])
            ->asArray()
            ->all();
    }

    public static function getEmployeesForSupplier($userId): array
    {
        $managerIds = Yii::$app->authManager->getUserIdsByRole('manager');

        return UserExtra::find()
            ->select(['userextra.id', 'userextra.user_id', 'userextra.supplier', 'user.username'])
            ->innerJoin('user', 'user.id = userextra.user_id')
            ->where(['userextra.supplier' => $userId])
            ->andWhere(['user.status' => 10])
            ->andWhere(['!=', 'userextra.user_id', $userId])
            ->andWhere(['not in', 'userextra.user_id', $managerIds])
            ->asArray()
            ->all();
    }
}