<?php

namespace common\forms;

use common\dto\UserDto;
use common\processors\UserViewProcessor;
use Yii;

class UserViewForm extends Form
{
    /**
     * @return \common\dto\UserDto
     * @throws \Exception
     * @throws \yii\web\UnauthorizedHttpException
     */
    public function process()
    {
        $userDto = UserDto::create()
            ->setId($this->getUser()->getId());

        $resultUserDto = UserViewProcessor::create()
            ->setUserDto($userDto)
            ->process();

        return $resultUserDto;
    }
}