<?php

namespace common\translators;

use common\dto\BillDto;
use common\models\Bill;

class BillActiveRecordToDtoTranslator
{
    /**
     * @param Bill $bill
     * @return BillDto
     */
    public static function translate(Bill $bill)
    {
        return BillDto::create()
            ->setId($bill->id)
            ->setUserId($bill->user_id)
            ->setName($bill->name)
            ->setBalance($bill->balance)
            ->setCreatedAt($bill->created_at)
            ->setUpdatedAt($bill->updated_at);
    }
}