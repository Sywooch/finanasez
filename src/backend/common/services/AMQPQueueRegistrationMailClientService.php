<?php

namespace common\services;

use common\dto\UserDto;
use common\enums\AMQPQueueEnum;
use PhpAmqpLib\Message\AMQPMessage;
use Yii;

class AMQPQueueRegistrationMailClientService extends AMQPQueueClientService
{
    /**
     * @param UserDto $userDto
     */
    public function send($userDto)
    {
        $textMessage = serialize($userDto);

        $amqpMessage = new AMQPMessage($textMessage);
        $this->channel->basic_publish($amqpMessage, '', $this->getQueue());
    }

    public function getQueue()
    {
        return AMQPQueueEnum::QUEUE_REGISTRATION_MAIL;
    }
}