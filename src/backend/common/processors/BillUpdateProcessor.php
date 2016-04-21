<?php

namespace common\processors;

use common\services\BillService;

class BillUpdateProcessor extends BaseBillProcessor
{
    protected function safeProcess()
    {
        $resultBillDto = BillService::create()
            ->update($this->billDto);

        return $resultBillDto;
    }
}