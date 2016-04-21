<?php

namespace common\enums;

use common\classes\Assert;

class TransactionTypeEnum
{
    const TYPE_SPEND    = 1;
    const TYPE_INCOME   = 2;
    const TYPE_TRANSFER = 3;

    public static $humanReadableTypeNames = [
        self::TYPE_SPEND     => 'Расход',
        self::TYPE_INCOME    => 'Доход',
        self::TYPE_TRANSFER  => 'Перемещение',
    ];

    public static $apiTypeToInternalMap = [
        'income'        => self::TYPE_INCOME,
        'spend'         => self::TYPE_SPEND,
        'transfer'      => self::TYPE_TRANSFER
    ];

    public static $internalToApiMap = [
        self::TYPE_INCOME        => 'income',
        self::TYPE_SPEND         => 'spend',
        self::TYPE_TRANSFER      => 'transfer'
    ];

    public static function getCodeByApiName($apiName)
    {
        if (isset(self::$apiTypeToInternalMap[$apiName])) {
            return self::$apiTypeToInternalMap[$apiName];
        } else {
            Assert::isUnreachable("Unknown transaction type: $apiName");
            return null;
        }
    }

    public static function getApiNameByCode($code)
    {
        if (isset(self::$internalToApiMap[$code])) {
            return self::$internalToApiMap[$code];
        } else {
            Assert::isUnreachable("Unknown transaction type: $code");
            return null;
        }
    }
}