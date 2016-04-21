<?php

namespace common\processors;

use common\services\BillService;

class BillViewProcessor extends BaseBillProcessor
{
    /**
     * @return \common\dto\BillDto
     */
    protected function safeProcess()
    {
        $resultBillDto = BillService::create()
            ->view($this->billDto);

        return $resultBillDto;
    }
}