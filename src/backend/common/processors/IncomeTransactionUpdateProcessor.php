<?php

namespace common\processors;

use common\services\BillBalanceService;
use common\vo\IncomeTransactionTypeVo;
use common\vo\SpendTransactionTypeVo;
use Yii;

class IncomeTransactionUpdateProcessor extends TypedTransactionUpdateProcessor
{
    protected function updateBills()
    {
        $billService = BillBalanceService::create()
            ->setBillId($this->newTransactionDto->getBillId());

        if ($this->oldTransactionDto->getType() instanceof IncomeTransactionTypeVo &&
            $this->newTransactionDto->getType() instanceof SpendTransactionTypeVo
        ) {
            $billService
                ->addSpend($this->oldTransactionDto->getAmount())
                ->addSpend($this->newTransactionDto->getAmount());
        }

        if ($this->newTransactionDto->getType() instanceof IncomeTransactionTypeVo &&
            $this->oldTransactionDto->getAmount() !== $this->newTransactionDto->getAmount()
        ) {
            $billService
                ->addSpend($this->oldTransactionDto->getAmount())
                ->addIncome($this->newTransactionDto->getAmount());
        }

        $billService->apply();
    }

}