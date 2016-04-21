<?php

namespace common\services;

interface QueueServerServiceInterface
{
    public function run();
    public function onReceive($message);
}