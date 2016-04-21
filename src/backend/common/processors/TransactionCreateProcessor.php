<?php

namespace common\processors;

use common\services\TransactionService;
use Yii;

class TransactionCreateProcessor extends BaseTransactionProcessor
{
    protected function safeProcess()
    {
        $resultTransactionDto = TransactionCreateProcessorFactory::build($this->transactionDto->getType())
            ->setTransactionDto($this->transactionDto)
            ->process();

        return $resultTransactionDto;
    }
}