<?php

namespace v1\requests;

use common\forms\DemoLoginForm;
use Yii;

class DemoLoginRequest extends LoginRequest
{
    protected function prepare()
    {
        $this->form = new DemoLoginForm();
    }
}