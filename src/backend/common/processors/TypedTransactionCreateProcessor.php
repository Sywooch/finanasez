<?php

namespace common\processors;

use common\services\TransactionService;
use Yii;

abstract class TypedTransactionCreateProcessor extends BaseTransactionProcessor
{
    protected function safeProcess()
    {
        $this->updateBills();

        $resultTransactionDto = TransactionService::create()->spawn($this->transactionDto);

        return $resultTransactionDto;
    }

    abstract protected function updateBills();
}