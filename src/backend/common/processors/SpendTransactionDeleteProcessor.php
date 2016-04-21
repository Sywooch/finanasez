<?php

namespace common\processors;

use Yii;
use common\services\BillBalanceService;

class SpendTransactionDeleteProcessor extends TypedTransactionDeleteProcessor
{
    protected function updateBills()
    {
        BillBalanceService::create()
            ->setBillId($this->transactionDto->getBillId())
            ->addIncome($this->transactionDto->getAmount())
            ->apply();
    }
}