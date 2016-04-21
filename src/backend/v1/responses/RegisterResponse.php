<?php

namespace v1\responses;

use common\dto\UserDto;
use common\responses\BaseResponse;

class RegisterResponse extends BaseResponse
{
    /**
     * @var UserDto
     */
    private $userDto;

    /**
     * @param UserDto $userDto
     * @return $this
     */
    public function setUserDto(UserDto $userDto)
    {
        $this->userDto = $userDto;
        return $this;
    }

    /**
     * @return array
     */
    public function getResponse()
    {
        return [
            'id' => $this->userDto->getId(),
            'username' => $this->userDto->getUsername(),
            'token' => $this->userDto->getToken(),
            'email' => $this->userDto->getEmail()
        ];
    }
}