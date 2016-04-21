<?php

namespace common\vo;

abstract class BaseTransactionTypeVo
{
    protected $type;

    public function __toString()
    {
        return (string) $this->type;
    }
}