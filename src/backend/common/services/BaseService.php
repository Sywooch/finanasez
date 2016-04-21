<?php

namespace common\services;

abstract class BaseService
{
    protected function __construct()
    {
    }

    public static function create()
    {
        return new static();
    }
}