<?php
namespace common\models;

use common\behaviors\DateTimeLocalBehavior;
use common\behaviors\TimezoneTimestampBehavior;
use Yii;
use common\behaviors\GuidBehavior;
use yii\db\ActiveRecord;

/**
 * @property string $id
 * @property string $user_id
 * @property string $category_id
 * @property string $bill_id
 * @property float  $amount
 * @property int $type
 * @property string $comment
 * @property string source_bill_id uses for transfer transaction: from source_bill_id to bill_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $datetime_local
 * @property
 * @property User $user
 * @property Bill $bill
 * @property Bill $sourceBill uses for transfer transaction: from sourceBill to bill
 * @property Category $category
 */
class Transaction extends ActiveRecord
{
    public static function tableName()
    {
        return 'transaction';
    }

    public function behaviors()
    {
        return [
            GuidBehavior::className(),
            TimezoneTimestampBehavior::className(),
            DateTimeLocalBehavior::className()
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'            => 'ID',
            'user_id'       => 'Имя пользователя',
            'bill_id'       => 'ID счета',
            'category_id'   => 'ID категории',
            'amount'        => 'Сумма',
            'comment'       => 'Комментарий'
        ];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['user_id' => 'id']);
    }

    public function getBill()
    {
        return $this->hasOne(Bill::className(), ['id' => 'bill_id']);
    }

    public function getSourceBill()
    {
        return $this->hasOne(Bill::className(), ['id' => 'source_bill_id']);
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function asEagerArray()
    {
        return $this->toArray() +
                ['bill' => $this->bill ? $this->bill->toArray() : []] +
                ['category' => $this->category ? $this->category->toArray() : []];
    }
}
