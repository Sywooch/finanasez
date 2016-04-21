<?php

namespace common\forms;

use common\dto\TransactionDto;
use common\models\Transaction;
use common\processors\TransactionViewProcessor;

class TransactionViewForm extends TransactionByIdForm
{
    /**
     * @return \common\dto\TransactionDto
     */
    public function process()
    {
        $transactionDto = TransactionDto::create()
            ->setId($this->id);

        $resultTransactionDto = TransactionViewProcessor::create()
            ->setTransactionDto($transactionDto)
            ->process();

        return $resultTransactionDto;
    }
}