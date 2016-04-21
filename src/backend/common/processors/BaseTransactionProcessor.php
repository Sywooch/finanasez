<?php

namespace common\processors;

use common\dto\TransactionDto;
use Yii;

abstract class BaseTransactionProcessor extends BaseProcessor
{
    /** @var TransactionDto */
    protected $transactionDto;

    /**
     * @param TransactionDto $transactionDto
     * @return $this
     */
    public function setTransactionDto(TransactionDto $transactionDto)
    {
        $this->transactionDto = $transactionDto;
        return $this;
    }
}