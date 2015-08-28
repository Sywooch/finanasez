<?php
namespace common\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        $user = static::findOne(['auth_key' => $token, 'status' => self::STATUS_ACTIVE]);

        if(null !== $user && !static::isAccessTokenValid($token)) {
            // if auth key is empty or expired, regenerate it & throw exception
            $user->regenerateAuthKeyAndSave();
            throw new ForbiddenHttpException("Auth token expired.");
        }
        return $user;

    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * check if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isAccessTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '|') + 1);
        $expire = Yii::$app->params['user.accessTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @return integer user id
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @return string authKey
     *
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * validates auth_key
     *
     * @return true if $authKey valid
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "auth_key" authentication key
     * @return null
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString() . '|' .time();
    }

    /**
     * Set new regenerated auth key & save it
     *
     * @return null
     */
    public function regenerateAuthKeyAndSave()
    {
        $this->generateAuthKey();
        $this->save();
    }

    /**
     * gets lifetime of auth_token
     * format: unix timestamp
     *
     * @return int
     */
    public function getAuthKeyLifeTime()
    {
        // auth key format: <auth_key>|<time>
        $token = $this->getAuthKey();
        return (int) substr($token, strrpos($token, '|') + 1);
    }


}
