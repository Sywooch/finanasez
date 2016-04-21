<?php

namespace common\vo;

use common\enums\TransactionTypeEnum;

class TransferTransactionTypeVo extends BaseTransactionTypeVo
{
    public function __construct()
    {
        $this->type = TransactionTypeEnum::TYPE_TRANSFER;
    }
}