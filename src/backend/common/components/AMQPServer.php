<?php

namespace common\components;

use yii\base\Component;
use yii\base\InvalidConfigException;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

class AMQPServer extends Component
{
    public $host;
    public $port;
    public $username;
    public $password;

    /** @var AMQPStreamConnection */
    public $connection;
    /** @var AMQPChannel */
    public $channel;

    public function init()
    {
        parent::init();

        if ($this->host === null ||
            $this->port === null ||
            $this->username === null ||
            $this->password === null
        ) {
            throw new InvalidConfigException('Each of host, port, username, password must be set.');
        }

        $this->connection = new AMQPStreamConnection(
            $this->host,
            $this->port,
            $this->username,
            $this->password
        );
        $this->channel = $this->connection->channel();
    }

}