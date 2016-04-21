<?php

namespace common\processors;

use Yii;

class TransactionDeleteProcessor extends BaseTransactionProcessor
{
    protected function safeProcess()
    {
        TransactionDeleteProcessorFactory::build($this->transactionDto->getType())
            ->setTransactionDto($this->transactionDto)
            ->process();
    }
}