<?php

namespace common\processors;

use common\services\BillService;

class BillListProcessor extends BaseBillProcessor
{
    /**
     * @return \common\dto\BillDto[]
     */
    protected function safeProcess()
    {
        $resultBillDtoArray = BillService::create()
            ->getForUser($this->billDto);

        return $resultBillDtoArray;
    }
}