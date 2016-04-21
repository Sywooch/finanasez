<?php

namespace common\services;

use common\models\Bill;
use common\repositories\BillRepository;

class BillBalanceService extends BaseService
{
    /** @var Bill */
    private $bill;
    /** @var string */
    private $billId;

    /** @var float */
    private $deltaAmount;

    public function setBill(Bill $bill)
    {
        $this->bill = $bill;
        return $this;
    }

    public function setBillId($billId)
    {
        $this->billId = $billId;
        return $this;
    }

    public function addIncome($income)
    {
        $this->deltaAmount += $income;
        return $this;
    }

    public function addSpend($spend)
    {
        $this->deltaAmount -= $spend;
        return $this;
    }

    public function apply()
    {
        $this->setupBill();
        $this->bill->balance += $this->deltaAmount;

        $this->bill->save();
    }

    private function setupBill()
    {
        if ($this->bill) {
            return;
        } elseif ($this->billId) {
            $this->bill = BillRepository::create()->get($this->billId);
        }

        if (!$this->bill) {
            throw new \LogicException("Bill not set or not found by id");
        }
    }
}