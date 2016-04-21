<?php

namespace common\forms;

use common\dto\BillDto;
use common\models\Bill;
use common\processors\BillUpdateProcessor;
use Yii;

class BillUpdateForm extends BillByIdForm
{
    public $name;
    public $balance;

    public function rules()
    {
        return array_merge(parent::rules(), [
            ['name', 'unique', 'targetClass' => Bill::className(), 'filter' => function($model) {
                /** @var \yii\db\ActiveQuery $model */
                $model
                    ->andWhere('id != :id', [':id' => $this->id])
                    ->andWhere('user_id = :user_id', [':user_id' => $this->getUser()->getId()]);

            }],
            ['balance', 'number', 'min' => 0],
        ]);
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Название счета',
            'balance' => 'Остаток на счете'
        ];
    }

    /**
     * @return \common\dto\BillDto
     */
    public function process()
    {
        $billDto = BillDto::create()
                    ->setId($this->id)
                    ->setUserId($this->getUser()->getId())
                    ->setName($this->name)
                    ->setBalance($this->balance);

        $resultBillDto = BillUpdateProcessor::create()
                    ->setBillDto($billDto)
                    ->process();

        return $resultBillDto;
    }
}