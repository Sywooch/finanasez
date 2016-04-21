<?php

namespace v1\requests;

use common\forms\UserUpdateForm;
use v1\responses\UserInfoResponse;
use Yii;

/**
 * @property UserUpdateForm $form
 */
class UserUpdateRequest extends BaseRequest
{
    protected function prepare()
    {
        $this->setForm(new UserUpdateForm());
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