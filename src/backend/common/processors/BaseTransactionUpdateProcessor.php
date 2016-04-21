<?php

namespace common\processors;

use common\dto\TransactionDto;

abstract class BaseTransactionUpdateProcessor extends BaseProcessor
{
    /** @var TransactionDto */
    protected $oldTransactionDto;
    /** @var TransactionDto */
    protected $newTransactionDto;

    /**
     * @param TransactionDto $newTransactionDto
     * @return $this
     */
    public function setNewTransactionDto(TransactionDto $newTransactionDto)
    {
        $this->newTransactionDto = $newTransactionDto;
        return $this;
    }

    /**
     * @param TransactionDto $oldTransactionDto
     * @return $this
     */
    public function setOldTransactionDto(TransactionDto $oldTransactionDto)
    {
        $this->oldTransactionDto = $oldTransactionDto;
        return $this;
    }
}