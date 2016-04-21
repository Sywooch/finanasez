<?php

namespace common\processors;

use common\services\BillBalanceService;
use common\vo\IncomeTransactionTypeVo;
use common\vo\SpendTransactionTypeVo;
use Yii;

class SpendTransactionUpdateProcessor extends TypedTransactionUpdateProcessor
{
    protected function updateBills()
    {
        $billService = BillBalanceService::create()
            ->setBillId($this->newTransactionDto->getBillId());

        if ($this->oldTransactionDto->getType() instanceof SpendTransactionTypeVo &&
            $this->newTransactionDto->getType() instanceof IncomeTransactionTypeVo
        ) {
            $billService
                ->addIncome($this->oldTransactionDto->getAmount())
                ->addIncome($this->newTransactionDto->getAmount());
        }

        if ($this->newTransactionDto->getType() instanceof SpendTransactionTypeVo &&
            $this->oldTransactionDto->getAmount() !== $this->newTransactionDto->getAmount()
        ) {
            $billService
                ->addIncome($this->oldTransactionDto->getAmount())
                ->addSpend($this->newTransactionDto->getAmount());
        }

        $billService->apply();
    }
}