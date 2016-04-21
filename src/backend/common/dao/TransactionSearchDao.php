<?php

namespace common\dao;

use common\dto\TransactionSearchDto;
use common\models\Transaction;

class TransactionSearchDao
{
    public static function create()
    {
        return new static();
    }

    /**
     * @param TransactionSearchDto $searchDto
     * @return Transaction[]
     */
    public function search(TransactionSearchDto $searchDto)
    {
        $query =  Transaction::find()
            ->leftJoin('category', 'category.id = transaction.category_id')
            ->leftJoin('bill', 'bill.id = transaction.bill_id')
            ->andWhere(['transaction.user_id' => $searchDto->getUserId()])
            ->andFilterWhere(['transaction.type' => $searchDto->getType()])
            ->andFilterWhere(['>=', 'transaction.datetime_local', $searchDto->getDateIntervalFrom()])
            ->andFilterWhere(['<=', 'transaction.datetime_local', $searchDto->getDateIntervalTill()])
            ->andFilterWhere(['>=', 'transaction.amount', $searchDto->getAmountFrom()])
            ->andFilterWhere(['<=', 'transaction.amount', $searchDto->getAmountTill()])
            ->andFilterWhere(['ilike', 'transaction.comment', $searchDto->getComment()])
            ->andFilterWhere(['=', 'category.name', $searchDto->getCategoryName()])
            ->orderBy('transaction.datetime_local desc, transaction.created_at desc, transaction.updated_at desc');

        if ($searchDto->getLimit() !== null) {
            $query->limit($searchDto->getLimit());
        }

        if ($searchDto->getOffset() !== null) {
            $query->offset($searchDto->getOffset());
        }

        $transactionArs = $query->all();

        return $transactionArs;
    }
}