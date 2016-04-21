<?php

namespace v1\requests;

use common\forms\BillCreateForm;
use v1\responses\BillInfoResponse;
use Yii;

class BillCreateRequest extends BaseRequest
{
    protected function prepare()
    {
        $this->setForm(new BillCreateForm());
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