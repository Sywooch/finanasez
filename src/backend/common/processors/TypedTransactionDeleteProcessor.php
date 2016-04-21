<?php

namespace common\processors;

use common\services\TransactionService;
use Yii;

abstract class TypedTransactionDeleteProcessor extends BaseTransactionProcessor
{
    protected function safeProcess()
    {
        $this->updateBills();

        TransactionService::create()
            ->delete($this->transactionDto);
    }

    abstract protected function updateBills();
}