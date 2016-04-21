<?php

namespace common\services;

use common\dto\UserDto;
use common\enums\AMQPQueueEnum;
use Yii;

class AMQPQueueRegistrationMailServerService extends AMQPQueueServerService
{
    public function onReceive($message)
    {
        try {
            $userDto = unserialize($message);
            if ($userDto === false) {
                throw new \Exception("Invalid message: expected UserDto, got false");
            } elseif (!($userDto instanceof UserDto)) {
                throw new \Exception("Invalid message: expected UserDto, got " . get_class($userDto));
            }
        } catch (\Exception $e) {
            Yii::error($e);
            return;
        }
        /** @var UserDto $userDto */

        RegistrationMailService::create()
            ->send($userDto);
    }

    public function getQueue()
    {
        return AMQPQueueEnum::QUEUE_REGISTRATION_MAIL;
    }
}