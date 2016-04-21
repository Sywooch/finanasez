<?php

namespace common\processors;

use common\services\BillService;

class BillCreateProcessor extends BaseBillProcessor
{
    protected function safeProcess()
    {
        $resultBillDto = BillService::create()
            ->spawn($this->billDto);

        return $resultBillDto;
    }
}