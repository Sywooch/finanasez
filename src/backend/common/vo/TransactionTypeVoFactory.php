<?php

namespace common\vo;

use common\enums\TransactionTypeEnum;

class TransactionTypeVoFactory
{
    public static function buildByCode($type)
    {
        switch ($type) {
            case TransactionTypeEnum::TYPE_SPEND:    return new SpendTransactionTypeVo();
            case TransactionTypeEnum::TYPE_INCOME:   return new IncomeTransactionTypeVo();
            case TransactionTypeEnum::TYPE_TRANSFER: return new TransferTransactionTypeVo();
        }
        throw new \LogicException("Unknown transaction type: $type");
    }

    public static function buildByApiName($apiName)
    {
        $type = TransactionTypeEnum::getCodeByApiName($apiName);
        return self::buildByCode($type);
    }
}