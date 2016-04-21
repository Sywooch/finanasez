<?php

namespace common\vo;

use common\enums\TransactionTypeEnum;

class IncomeTransactionTypeVo extends BaseTransactionTypeVo
{
    public function __construct()
    {
        $this->type = TransactionTypeEnum::TYPE_INCOME;
    }
}