<?php

namespace common\processors;

use common\dto\TransactionDto;
use common\services\TransactionService;
use Yii;

abstract class TypedTransactionUpdateProcessor extends BaseTransactionUpdateProcessor
{
    protected function safeProcess()
    {
        $this->updateBills();

        $resultTransactionDto = TransactionService::create()
            ->update($this->newTransactionDto);

        return $resultTransactionDto;
    }

    abstract protected function updateBills();
}