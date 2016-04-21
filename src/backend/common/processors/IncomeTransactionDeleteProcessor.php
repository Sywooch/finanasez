<?php

namespace common\processors;

use common\services\BillBalanceService;
use Yii;

class IncomeTransactionDeleteProcessor extends TypedTransactionDeleteProcessor
{
    protected function updateBills()
    {
        BillBalanceService::create()
            ->setBillId($this->transactionDto->getBillId())
            ->addSpend($this->transactionDto->getAmount())
            ->apply();
    }
}