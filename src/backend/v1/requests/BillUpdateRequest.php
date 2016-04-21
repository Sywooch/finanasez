<?php

namespace v1\requests;

use common\forms\BillUpdateForm;
use v1\responses\BillInfoResponse;
use Yii;

class BillUpdateRequest extends BaseRequest
{
    protected function prepare()
    {
        $this->setForm(new BillUpdateForm());
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