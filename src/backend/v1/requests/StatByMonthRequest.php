<?php

namespace v1\requests;

use common\forms\StatByMonthForm;
use v1\responses\StatByMonthResponse;
use Yii;

class StatByMonthRequest extends BaseRequest
{
    protected function prepare()
    {
        $this->setForm(new StatByMonthForm());
    }

    protected function safeProcess()
    {
        $data =  $this->form->process();

        $response = StatByMonthResponse::create()
            ->setData($data)
            ->getResponse();

        return $response;
    }
}