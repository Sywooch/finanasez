<?php

namespace v1\requests;

use common\forms\CategoryDeleteForm;
use Yii;

class CategoryDeleteRequest extends BaseRequest
{
    protected function prepare()
    {
        $this->setForm(new CategoryDeleteForm());
    }

    protected function safeProcess()
    {
        $this->form->process();
    }
}