<?php

namespace v1\requests;

use common\forms\BillDeleteForm;
use Yii;

class BillDeleteRequest extends BaseRequest
{
    protected function prepare()
    {
        $this->setForm(new BillDeleteForm());
    }

    protected function safeProcess()
    {
        $this->form->process();
    }
}