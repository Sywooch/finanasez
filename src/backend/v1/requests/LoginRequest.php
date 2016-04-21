<?php

namespace v1\requests;

use common\forms\LoginForm;
use v1\responses\UserInfoResponse;
use Yii;

class LoginRequest extends BaseRequest
{
    protected function prepare()
    {
        $this->setForm(new LoginForm());
    }

    protected function safeProcess()
    {
        $userDto = $this->form->process();

        $response = UserInfoResponse::create()
            ->setUserDto($userDto)
            ->getResponse();

        return $response;
    }

}