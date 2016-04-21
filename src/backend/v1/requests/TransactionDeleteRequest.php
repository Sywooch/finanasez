<?php

namespace v1\requests;

use common\forms\TransactionDeleteForm;
use Yii;

class TransactionDeleteRequest extends BaseRequest
{
    protected function prepare()
    {
        $this->setForm(new TransactionDeleteForm());
    }

    protected function safeProcess()
    {
        $this->form->process();
    }
}