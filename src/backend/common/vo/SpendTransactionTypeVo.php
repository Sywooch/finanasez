<?php

namespace common\vo;

use common\enums\TransactionTypeEnum;

class SpendTransactionTypeVo extends BaseTransactionTypeVo
{
    public function __construct()
    {
        $this->type = TransactionTypeEnum::TYPE_SPEND;
    }
}