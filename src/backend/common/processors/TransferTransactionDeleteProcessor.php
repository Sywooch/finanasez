<?php

namespace common\processors;

use common\services\BillBalanceService;
use Yii;

class TransferTransactionDeleteProcessor extends TypedTransactionDeleteProcessor
{
    protected function updateBills()
    {
        BillBalanceService::create()
            ->setBillId($this->transactionDto->getBillId())
            ->addSpend($this->transactionDto->getAmount())
            ->apply();

        BillBalanceService::create()
            ->setBillId($this->transactionDto->getSourceBillId())
            ->addIncome($this->transactionDto->getAmount())
            ->apply();
    }
}