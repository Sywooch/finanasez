<?php

namespace common\processors;

use common\vo\IncomeTransactionTypeVo;
use common\vo\SpendTransactionTypeVo;
use common\vo\BaseTransactionTypeVo;
use common\vo\TransferTransactionTypeVo;

class TransactionCreateProcessorFactory
{
    /**
     * @param BaseTransactionTypeVo $typeVo
     * @return TypedTransactionCreateProcessor
     */
    public static function build(BaseTransactionTypeVo $typeVo)
    {
        if ($typeVo instanceof TransferTransactionTypeVo) {
            return TransferTransactionCreateProcessor::create();
        } elseif ($typeVo instanceof SpendTransactionTypeVo) {
            return SpendTransactionCreateProcessor::create();
        } elseif ($typeVo instanceof IncomeTransactionTypeVo) {
            return IncomeTransactionCreateProcessor::create();
        } else {
            throw new \LogicException('Unknown transaction type class: ' . get_class($typeVo));
        }
    }
}