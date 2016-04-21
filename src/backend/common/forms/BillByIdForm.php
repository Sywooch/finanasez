<?php

namespace common\forms;

use common\models\Bill;
use common\repositories\BillRepository;
use Yii;

abstract class BillByIdForm extends Form
{
    public $id;

    public function rules()
    {
        return [
            ['id', 'required'],
            ['id', 'uuid'],
            ['id', 'validateBillId'],
        ];
    }

    public function validateBillId()
    {
        if (!$this->hasErrors()) {
            $bill = BillRepository::create()->getByIdAndUserId($this->id, $this->getUser()->getId());

            if (!$bill) {
                $this->addError('id', 'Счет не найден или принадлежит другому пользователю.');
            }
        }
    }
}