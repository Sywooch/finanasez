<?php
namespace common\classes;

class Guid
{
    public static function random()
    {
        return
            sprintf('%08x-%04x-%04x-%04x-%04x%08x',
                mt_rand(0, 0xffffffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff),
                mt_rand(0, 0xffff), mt_rand(0, 0xffffffff)
            );
    }
}