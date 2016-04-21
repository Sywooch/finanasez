<?php

namespace common\services;

use Yii;
use PhpAmqpLib\Channel\AMQPChannel;

abstract class AMQPQueueService extends BaseService
{
    /** @var AMQPChannel */
    protected $channel;

    /**
     * QueueServerService constructor.
     */
    final public function __construct()
    {
        $this->channel = Yii::$app->amqp->channel;
        $this->channel->queue_declare($this->getQueue(), false, false, false, false);
    }

    abstract public function getQueue();
}