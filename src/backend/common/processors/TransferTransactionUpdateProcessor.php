<?php

namespace common\processors;

use common\services\BillBalanceService;
use Yii;

class TransferTransactionUpdateProcessor extends TypedTransactionUpdateProcessor
{
    protected function updateBills()
    {
        $amountDifference = $this->oldTransactionDto->getAmount() - $this->newTransactionDto->getAmount();

        if ($amountDifference !== 0) {
            BillBalanceService::create()
                ->setBillId($this->newTransactionDto->getSourceBillId())
                ->addIncome($amountDifference)
                ->apply();

            BillBalanceService::create()
                ->setBillId($this->newTransactionDto->getBillId())
                ->addSpend($amountDifference)
                ->apply();
        }
    }
}