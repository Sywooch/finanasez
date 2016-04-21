<?php

namespace common\processors;

use common\services\UserService;

class UserCreateProcessor extends BaseUserProcessor
{
    protected function safeProcess()
    {
        $resultUserDto = UserService::create()
            ->spawn($this->userDto);

        return $resultUserDto;
    }
}