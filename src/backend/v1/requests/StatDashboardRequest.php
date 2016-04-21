<?php

namespace v1\requests;

use common\forms\StatDashboardForm;
use v1\responses\StatDashboardResponse;
use Yii;

class StatDashboardRequest extends BaseRequest
{
    protected function prepare()
    {
        $this->setForm(new StatDashboardForm());
    }

    protected function safeProcess()
    {
        $data = $this->form->process();

        $response = StatDashboardResponse::create()
            ->setData($data)
            ->getResponse();

        return $response;
    }
}