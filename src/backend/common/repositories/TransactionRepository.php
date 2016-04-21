<?php

namespace common\repositories;

use common\models\Transaction;

/**
 * @static TransactionRepository create()
 */
class TransactionRepository extends BaseRepository
{
    /**
     * @param $id
     * @return null|Transaction
     */
    public function get($id)
    {
        return Transaction::findOne($id);
    }

    /**
     * @param $id
     * @param $userId
     * @return null|Transaction
     */
    public function getByIdAndUserId($id, $userId)
    {
        return Transaction::findOne([
            'id'      => $id,
            'user_id' => $userId
        ]);
    }
}