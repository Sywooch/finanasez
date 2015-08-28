<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "operation".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $category_id
 * @property integer $bill_id
 * @property string $amount
 * @property string $comment
 *
 * @property Category $category
 * @property Bill $bill
 * @property User $user
 */
class Operation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'operation';
    }


    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            // i don't need timestamp behaviour, there will be datepicker in frontend
            // and i'll set it manually
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'category_id', 'bill_id', 'amount'], 'required'],
            [['user_id', 'category_id', 'bill_id'], 'integer'],
            [['amount'], 'number'],
            [['created_at', 'updated_at'], 'integer'],
            [['comment'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'category_id' => 'Category ID',
            'bill_id' => 'Bill ID',
            'amount' => 'Amount',
            'comment' => 'Comment',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBill()
    {
        return $this->hasOne(Bill::className(), ['id' => 'bill_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * event after save
     *
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        // refresh money in bill table
        if($insert) {
            $bill       = $this->bill;
            $category   = $this->category;
            $amount     = $this->amount;
            // if category is spending, then amount < 0 else category is earning and amount > 0
            if($category->type=='out') {
                $amount = -$amount;
            }

            $bill->money = $bill->money + $amount;
            $bill->save();
        }

    }

}