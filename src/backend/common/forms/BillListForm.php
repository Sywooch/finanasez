<?php

namespace common\forms;

use common\dto\BillDto;
use common\processors\BillListProcessor;
use Yii;

class BillListForm extends Form
{
    /**
     * @return \common\dto\BillDto[]
     */
    public function process()
    {
        $billDto = BillDto::create()
            ->setUserId($this->getUser()->getId());

        $resultBillDtoArray = BillListProcessor::create()
            ->setBillDto($billDto)
            ->process();

        return $resultBillDtoArray;
    }
}