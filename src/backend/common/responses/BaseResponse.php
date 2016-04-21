<?php

namespace common\responses;

abstract class BaseResponse implements ResponseInterface
{
    public static function className()
    {
        return get_called_class();
    }

    public static function create()
    {
        return new static();
    }
}