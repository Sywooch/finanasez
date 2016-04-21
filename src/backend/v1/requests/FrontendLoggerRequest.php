<?php

namespace v1\requests;

use common\forms\FrontendLoggerForm;
use Yii;

class FrontendLoggerRequest extends BaseRequest
{
    protected function prepare()
    {
        $this->setForm(new FrontendLoggerForm());
    }

    protected function safeProcess()
    {
        $this->form->process();
    }
}