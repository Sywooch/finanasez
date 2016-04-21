<?php

namespace common\processors;

use Yii;
use common\services\BillBalanceService;

class SpendTransactionCreateProcessor extends TypedTransactionCreateProcessor
{
    protected function updateBills()
    {
        BillBalanceService::create()
            ->setBillId($this->transactionDto->getBillId())
            ->addSpend($this->transactionDto->getAmount())
            ->apply();
    }
}