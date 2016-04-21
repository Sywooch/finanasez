<?php

namespace common\forms;

use common\dto\BillDto;
use common\processors\BillViewProcessor;
use Yii;

class BillViewForm extends BillByIdForm
{
    /**
     * @return \common\dto\BillDto
     */
    public function process()
    {
        $billDto = BillDto::create()
            ->setId($this->id);

        $resultBillDto = BillViewProcessor::create()
            ->setBillDto($billDto)
            ->process();

        return $resultBillDto;
    }
}