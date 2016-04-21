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
 * @property bool   $is_income
 * @property string $created_at
 * @property string $updated_at
 * @property
 * @property User $user
 */
class Category extends ActiveRecord
{
    public static function tableName()
    {
        return 'category';
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
            'is_income' => 'Категория для дохода',
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'id']);
    }
}
