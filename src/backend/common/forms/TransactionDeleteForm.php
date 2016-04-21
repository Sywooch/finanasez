<?php

namespace common\forms;

use common\dto\TransactionDto;
use common\processors\TransactionDeleteProcessor;
use common\vo\TransactionTypeVoFactory;

class TransactionDeleteForm extends TransactionByIdForm
{
    public function process()
    {
        $transactionDto = TransactionDto::create()
            ->setId($this->id)
            ->setBillId($this->transaction->bill_id)
            ->setSourceBillId($this->transaction->source_bill_id)
            ->setAmount($this->transaction->amount)
            ->setType(TransactionTypeVoFactory::buildByCode($this->transaction->type));

        TransactionDeleteProcessor::create()
            ->setTransactionDto($transactionDto)
            ->process();
    }
}