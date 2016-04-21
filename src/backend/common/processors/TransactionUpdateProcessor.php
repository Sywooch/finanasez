<?php

namespace common\processors;

use Yii;

class TransactionUpdateProcessor extends BaseTransactionUpdateProcessor
{
    /**
     * @return mixed
     * @throws \common\dto\TransactionDto
     */
    protected function safeProcess()
    {
        $transactionType = $this->oldTransactionDto->getType();

        $resultTransactionDto = TransactionUpdateProcessorFactory::build($transactionType)
            ->setNewTransactionDto($this->newTransactionDto)
            ->setOldTransactionDto($this->oldTransactionDto)
            ->process();

        return $resultTransactionDto;
    }
}