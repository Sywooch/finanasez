<?php

namespace common\services;

use Yii;

abstract class AMQPQueueServerService extends AMQPQueueService implements QueueServerServiceInterface
{
    final public function run()
    {
        $callback = function($msg) {
            $this->onReceive($msg->body);
        };

        $this->channel->basic_consume($this->getQueue(), '', false, true, false, false, $callback);

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }
}