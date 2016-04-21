<?php

namespace common\repositories;

use common\models\Bill;

/**
 * @static BillRepository create()
 */
class BillRepository extends BaseRepository
{
    /**
     * @param $id
     * @return null|Bill
     */
    public function get($id)
    {
        return Bill::findOne($id);
    }

    /**
     * @param $id
     * @param $userId
     * @return null|Bill
     */
    public function getByIdAndUserId($id, $userId)
    {
        return Bill::findOne([
            'id'        => $id,
            'user_id'   => $userId
        ]);
    }
}