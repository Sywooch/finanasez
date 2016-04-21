<?php
namespace common\models;

use common\behaviors\TimezoneTimestampBehavior;
use common\classes\Guid;
use common\validators\UuidValidator;
use Yii;
use common\behaviors\GuidBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * @property string $id
 * @property string $username
 * @property string $auth_key
 * @property string $token
 * @property string $password_hash
 * @property string $email
 * @property string $created_at
 * @property string $updated_at
 * @property
 * @property Bill[] $bills
 * @property Category[] $categories
 */
class User extends ActiveRecord implements IdentityInterface
{
    public static function tableName()
    {
        return 'user';
    }

    public function behaviors()
    {
        return [
            GuidBehavior::className(),
            TimezoneTimestampBehavior::className(),
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Имя пользователя',
            'email' => 'Email',
            'token' => 'Секретный ключ'
        ];
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }


    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function generateToken()
    {
        $this->token = Guid::random();
    }

    public static function findIdentity($id)
    {
        if ((new UuidValidator())->validate($id)) {
            return static::findOne(['id' => $id]);
        }
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        if ((new UuidValidator())->validate($token)) {
            return static::findOne(['token' => $token]);
        }
    }

    public function getBills()
    {
        return $this->hasMany(Bill::className(), ['user_id' => 'id']);
    }

    public function getCategories()
    {
        return $this->hasMany(Category::className(), ['user_id' => 'id']);
    }
}