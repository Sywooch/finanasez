<?php

namespace common\forms;

use common\dto\BillDto;
use common\processors\BillDeleteProcessor;
use Yii;

class BillDeleteForm extends BillByIdForm
{
    public function process()
    {
        $billDto = BillDto::create()
                    ->setId($this->id)
                    ->setUserId($this->getUser()->getId());

        BillDeleteProcessor::create()
                    ->setBillDto($billDto)
                    ->process();
    }
}