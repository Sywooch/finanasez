<?php

namespace common\processors;

use common\services\UserService;

class UserUpdateProcessor extends BaseUserProcessor
{
    /**
     * @return \common\dto\UserDto
     */
    protected function safeProcess()
    {
        $resultUserDto = UserService::create()
            ->update($this->userDto);

        return $resultUserDto;
    }
}