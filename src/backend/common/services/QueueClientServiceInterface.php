<?php

namespace common\services;

interface QueueClientServiceInterface
{
    public function send($message);
}