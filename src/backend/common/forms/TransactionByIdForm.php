<?php

namespace common\forms;

use common\models\Transaction;
use common\repositories\TransactionRepository;

abstract class TransactionByIdForm extends Form
{
    public $id;

    /** @var Transaction */
    protected $transaction;

    public function rules()
    {
        return [
            ['id', 'required'],
            ['id', 'uuid'],
            ['id', 'validateTransactionId']
        ];
    }

    public function validateTransactionId()
    {
        if (!$this->hasErrors()) {
            $this->transaction = TransactionRepository::create()->getByIdAndUserId($this->id, $this->getUser()->getId());

            if (!$this->transaction) {
                $this->addError('id', 'Транзакция не найдена или принадлежит другому пользователю.');
            }
        }
    }
}