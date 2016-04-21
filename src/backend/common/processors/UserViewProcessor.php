<?php

namespace common\processors;

use common\services\UserService;

class UserViewProcessor extends BaseUserProcessor
{
    /**
     * @return \common\dto\UserDto
     */
    protected function safeProcess()
    {
        $resultUserDto = UserService::create()
            ->view($this->userDto);

        return $resultUserDto;
    }
}