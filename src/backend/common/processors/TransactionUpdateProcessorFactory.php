<?php

namespace common\processors;

use common\classes\Assert;
use common\vo\IncomeTransactionTypeVo;
use common\vo\SpendTransactionTypeVo;
use common\vo\BaseTransactionTypeVo;
use common\vo\TransferTransactionTypeVo;

class TransactionUpdateProcessorFactory
{
    /**
     * @param BaseTransactionTypeVo $typeVo
     * @return TypedTransactionUpdateProcessor
     */
    public static function build(BaseTransactionTypeVo $typeVo)
    {
        if ($typeVo instanceof TransferTransactionTypeVo) {
            return TransferTransactionUpdateProcessor::create();
        } elseif ($typeVo instanceof SpendTransactionTypeVo) {
            return SpendTransactionUpdateProcessor::create();
        } elseif ($typeVo instanceof IncomeTransactionTypeVo) {
            return IncomeTransactionUpdateProcessor::create();
        } else {
            Assert::isUnreachable('Unknown transaction type class: ' . get_class($typeVo));
        }
    }
}