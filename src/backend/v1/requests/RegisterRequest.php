<?php

namespace v1\requests;

use common\forms\UserCreateForm;
use v1\responses\UserInfoResponse;
use Yii;

/**
 * @property UserCreateForm $form
 */
class RegisterRequest extends BaseRequest
{
    protected function prepare()
    {
        $this->setForm(new UserCreateForm());
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