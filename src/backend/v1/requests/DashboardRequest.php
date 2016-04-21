<?php

namespace v1\requests;

use common\forms\UserViewForm;
use v1\responses\UserInfoResponse;
use Yii;

class DashboardRequest extends BaseRequest
{
    protected function prepare()
    {
        $this->setForm(new UserViewForm());
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