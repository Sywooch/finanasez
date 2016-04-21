<?php

namespace common\processors;

use common\dto\UserDto;

abstract class BaseUserProcessor extends BaseProcessor
{
    /** @var UserDto */
    protected $userDto;

    /**
     * @param UserDto $userDto
     * @return $this
     */
    public function setUserDto(UserDto $userDto)
    {
        $this->userDto = $userDto;
        return $this;
    }
}