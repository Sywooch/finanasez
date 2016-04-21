<?php

namespace common\processors;

use common\services\BillBalanceService;
use Yii;

class IncomeTransactionCreateProcessor extends TypedTransactionCreateProcessor
{
    protected function updateBills()
    {
        BillBalanceService::create()
            ->setBillId($this->transactionDto->getBillId())
            ->addIncome($this->transactionDto->getAmount())
            ->apply();
    }
}