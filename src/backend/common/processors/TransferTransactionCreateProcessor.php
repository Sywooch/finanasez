<?php

namespace common\processors;

use common\services\BillBalanceService;
use Yii;

class TransferTransactionCreateProcessor extends TypedTransactionCreateProcessor
{
    protected function updateBills()
    {
        BillBalanceService::create()
            ->setBillId($this->transactionDto->getSourceBillId())
            ->addSpend($this->transactionDto->getAmount())
            ->apply();

        BillBalanceService::create()
            ->setBillId($this->transactionDto->getBillId())
            ->addIncome($this->transactionDto->getAmount())
            ->apply();
    }
}