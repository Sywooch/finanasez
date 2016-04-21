<?php

namespace common\translators;

use common\dto\TransactionDto;
use common\models\Transaction;
use common\vo\TransactionTypeVoFactory;

class TransactionActiveRecordToDtoTranslator
{
    /**
     * @param Transaction $transaction
     * @return TransactionDto
     */
    public static function translate(Transaction $transaction)
    {
        return TransactionDto::create()
            ->setId($transaction->id)
            ->setUserId($transaction->user_id)
            ->setCategoryId($transaction->category_id)
            ->setBillId($transaction->bill_id)
            ->setAmount($transaction->amount)
            ->setType(TransactionTypeVoFactory::buildByCode($transaction->type))
            ->setComment($transaction->comment)
            ->setSourceBillId($transaction->source_bill_id)
            ->setCreatedAt($transaction->created_at)
            ->setUpdatedAt($transaction->updated_at)
            ->setDatetimeLocal($transaction->datetime_local);
    }
}