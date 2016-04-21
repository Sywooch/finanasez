<?php

namespace common\forms;

use common\dto\TransactionDto;
use common\processors\TransactionCreateProcessor;

class TransactionCreateForm extends TransactionCreateUpdateForm
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            [['amount', 'type', 'bill_id'], 'required'],
        ]);
    }

    public function process()
    {
        $transactionDto = TransactionDto::create()
            ->setUserId($this->getUser()->getId())
            ->setType($this->type ? : null)
            ->setAmount($this->amount)
            ->setBillId($this->bill_id)
            ->setSourceBillId($this->source_bill_id)
            ->setCategoryId($this->category_id)
            ->setComment($this->comment)
            ->setDatetimeLocal($this->date);

        $resultTransactionDto = TransactionCreateProcessor::create()
            ->setTransactionDto($transactionDto)
            ->process();

        return $resultTransactionDto;
    }
}