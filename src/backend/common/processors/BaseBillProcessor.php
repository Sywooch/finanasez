<?php

namespace common\processors;

use common\dto\BillDto;

abstract class BaseBillProcessor extends BaseProcessor
{
    /** @var BillDto */
    protected $billDto;

    /**
     * @param $billDto
     * @return $this
     */
    public function setBillDto($billDto)
    {
        $this->billDto = $billDto;
        return $this;
    }
}