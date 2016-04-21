<?php

namespace v1\requests;

use common\forms\BillListForm;
use v1\responses\BillListResponse;
use Yii;

class BillListRequest extends BaseRequest
{
    protected function prepare()
    {
        $this->setForm(new BillListForm());
    }

    protected function safeProcess()
    {
        $billDtoArray = $this->form->process();

        $result = BillListResponse::create()
            ->setBillDtoArray($billDtoArray)
            ->getResponse();

        return $result;
    }
}