<?php

namespace common\dto;

abstract class BaseDto
{
    public static function create()
    {
        return new static();
    }
}