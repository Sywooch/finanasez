<?php
namespace common\models;

use common\behaviors\TimezoneTimestampBehavior;
use Yii;
use common\behaviors\GuidBehavior;
use yii\db\ActiveRecord;

/**
 * @property string $id
 * @property string $user_id
 * @property string $name
 * @property float  $balance
 * @property string $created_at
 * @property string $updated_at
 * @property
 * @property User $user
 * @property Transaction[] $transactions
 */
class Bill extends ActiveRecord
{
    public static function tableName()
    {
        return 'bill';
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
            'id'        => 'ID',
            'user_id'   => 'Имя пользователя',
            'name'      => 'Название счета',
            'balance'   => 'Остаток на счете'
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'id']);
    }

    public function getTransactions()
    {
        return $this->hasMany(Transaction::className(), ['bill_id' => 'id']);
    }
}
