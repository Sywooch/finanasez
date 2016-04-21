<?php

namespace common\processors;

use common\services\BillService;

class BillDeleteProcessor extends BaseBillProcessor
{
    protected function safeProcess()
    {
        BillService::create()
            ->delete($this->billDto);
    }
}