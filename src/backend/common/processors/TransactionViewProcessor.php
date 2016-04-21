<?php

namespace common\processors;

use common\services\TransactionService;
use Yii;

class TransactionViewProcessor extends BaseTransactionProcessor
{
    /**
     * @return \common\dto\TransactionDto
     */
    protected function safeProcess()
    {
        $resultTransactionDto = TransactionService::create()
            ->view($this->transactionDto);

        return $resultTransactionDto;
    }
}