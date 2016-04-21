<?php

namespace common\forms;

use common\dto\BillDto;
use common\models\Bill;
use common\processors\BillCreateProcessor;
use Yii;

class BillCreateForm extends Form
{
    public $name;
    public $balance;

    public function rules()
    {
        return [
            [['name'], 'required'],
            ['name', 'unique', 'targetClass' => Bill::className(), 'filter' => ['user_id' => Yii::$app->user->identity->getId()]],
            ['balance', 'number', 'min' => 0],
        ];
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
                    ->setUserId($this->getUser()->getId())
                    ->setName($this->name)
                    ->setBalance((int)$this->balance);

        $resultBillDto = BillCreateProcessor::create()
                    ->setBillDto($billDto)
                    ->process();

        return $resultBillDto;
    }
}