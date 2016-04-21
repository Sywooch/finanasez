<?php

namespace v1\requests;

use common\forms\BillViewForm;
use v1\responses\BillInfoResponse;
use Yii;

class BillViewRequest extends BaseRequest
{
    protected function prepare()
    {
        $this->setForm(new BillViewForm());
    }

    protected function safeProcess()
    {
        $billDto = $this->form->process();

        $response = BillInfoResponse::create()
            ->setBillDto($billDto)
            ->getResponse();

        return $response;
    }
}