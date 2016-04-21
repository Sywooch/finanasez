<?php

namespace common\processors;

use common\vo\IncomeTransactionTypeVo;
use common\vo\SpendTransactionTypeVo;
use common\vo\BaseTransactionTypeVo;
use common\vo\TransferTransactionTypeVo;

class TransactionDeleteProcessorFactory
{
    /**
     * @param BaseTransactionTypeVo $typeVo
     * @return TypedTransactionDeleteProcessor
     */
    public static function build(BaseTransactionTypeVo $typeVo)
    {
        if ($typeVo instanceof TransferTransactionTypeVo) {
            return TransferTransactionDeleteProcessor::create();
        } elseif ($typeVo instanceof SpendTransactionTypeVo) {
            return SpendTransactionDeleteProcessor::create();
        } elseif ($typeVo instanceof IncomeTransactionTypeVo) {
            return IncomeTransactionDeleteProcessor::create();
        } else {
            throw new \LogicException('Unknown transaction type class: ' . get_class($typeVo));
        }
    }
}