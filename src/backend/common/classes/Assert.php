<?php

namespace common\classes;

use Exception;

final class Assert
{
    public static function isTrue($boolean, $message = null)
    {
        if ($boolean !== true)
            throw new Exception(
                $message.', '.self::dumpArgument($boolean)
            );
    }
    public static function isFalse($boolean, $message = null)
    {
        if ($boolean !== false)
            throw new Exception(
                $message.', '.self::dumpArgument($boolean)
            );
    }
    public static function isNotFalse($boolean, $message = null)
    {
        if ($boolean === false)
            throw new Exception(
                $message.', '.self::dumpArgument($boolean)
            );
    }
    public static function isNull($variable, $message = null)
    {
        if ($variable !== null)
            throw new Exception(
                $message.', '.self::dumpArgument($variable)
            );
    }
    public static function isEmpty($variable, $message = null)
    {
        if (!empty($variable))
            throw new Exception(
                $message.', '.self::dumpArgument($variable)
            );
    }
    public static function isNotEmpty($variable, $message = null)
    {
        if (empty($variable))
            throw new Exception(
                $message.', '.self::dumpArgument($variable)
            );
    }
    public static function isIndexExists($array, $key, $message = null)
    {
        Assert::isArray($array);
        if (!array_key_exists($key, $array))
            throw new Exception(
                $message.', '.self::dumpArgument($key)
            );
    }
    public static function isNotNull($variable, $message = null)
    {
        if ($variable === null)
            throw new Exception($message);
    }
    public static function isScalar($variable, $message = null)
    {
        if (!is_scalar($variable))
            throw new Exception(
                $message.', '.self::dumpArgument($variable)
            );
    }
    public static function isArray($variable, $message = null)
    {
        if (!is_array($variable))
            throw new Exception(
                $message.', '.self::dumpArgument($variable)
            );
    }
    public static function isNotEmptyArray(&$variable, $message = null)
    {
        self::isArray($variable, $message);
        if (!$variable)
            throw new Exception(
                $message.', '.self::dumpArgument($variable)
            );
    }
    public static function isInteger($variable, $message = null)
    {
        if (
        !(
            is_numeric($variable)
            && $variable == (int) $variable
        )
        )
            throw new Exception(
                $message.', '.self::dumpArgument($variable)
            );
    }
    public static function isPositiveInteger($variable, $message = null)
    {
        if (
            !self::checkInteger($variable)
            || $variable < 0
        )
            throw new Exception(
                $message.', '.self::dumpArgument($variable)
            );
    }
    public static function isFloat($variable, $message = null)
    {
        if (!self::checkFloat($variable))
            throw new Exception(
                $message.', '.self::dumpArgument($variable)
            );
    }
    public static function isString($variable, $message = null)
    {
        if (!is_string($variable))
            throw new Exception(
                $message.', '.self::dumpArgument($variable)
            );
    }
    public static function isBoolean($variable, $message = null)
    {
        if (!($variable === true || $variable === false))
            throw new Exception(
                $message.', '.self::dumpArgument($variable)
            );
    }
    public static function isTernaryBase($variable, $message = null)
    {
        if (
        !(
            ($variable === true)
            || ($variable === false)
            || ($variable === null)
        )
        )
            throw new Exception(
                $message.', '.self::dumpArgument($variable)
            );
    }
    public static function brothers($first, $second, $message = null)
    {
        if (get_class($first) !== get_class($second))
            throw new Exception(
                $message.', '.self::dumpOppositeArguments($first, $second)
            );
    }
    public static function isEqual($first, $second, $message = null)
    {
        if ($first != $second)
            throw new Exception(
                $message.', '.self::dumpOppositeArguments($first, $second)
            );
    }
    public static function isNotEqual($first, $second, $message = null)
    {
        if ($first == $second)
            throw new Exception(
                $message.', '.self::dumpOppositeArguments($first, $second)
            );
    }
    public static function isSame($first, $second, $message = null)
    {
        if ($first !== $second)
            throw new Exception(
                $message.', '.self::dumpOppositeArguments($first, $second)
            );
    }
    public static function isNotSame($first, $second, $message = null)
    {
        if ($first === $second)
            throw new Exception(
                $message.', '.self::dumpOppositeArguments($first, $second)
            );
    }
    public static function isTypelessEqual($first, $second, $message = null)
    {
        if ($first != $second)
            throw new Exception(
                $message.', '.self::dumpOppositeArguments($first, $second)
            );
    }
    public static function isLesser($first, $second, $message = null)
    {
        if (!($first < $second))
            throw new Exception(
                $message.', '.self::dumpOppositeArguments($first, $second)
            );
    }
    public static function isGreater($first, $second, $message = null)
    {
        if (!($first > $second))
            throw new Exception(
                $message.', '.self::dumpOppositeArguments($first, $second)
            );
    }
    public static function isLesserOrEqual($first, $second, $message = null)
    {
        if (!($first <= $second))
            throw new Exception(
                $message.', '.self::dumpOppositeArguments($first, $second)
            );
    }
    public static function isGreaterOrEqual($first, $second, $message = null)
    {
        if (!($first >= $second))
            throw new Exception(
                $message.', '.self::dumpOppositeArguments($first, $second)
            );
    }
    public static function classExists($className, $message = null)
    {
        if (!class_exists($className, true))
            throw new Exception(
                $message.', class "'.$className.'" does not exists'
            );
    }
    public static function methodExists($object, $method, $message = null)
    {
        if (!method_exists($object, $method))
            throw new Exception(
                $message.', method "'.get_class($object).'::'.$method.'()" does not exists'
            );
    }
    public static function isUnreachable($message = 'unreachable code reached')
    {
        throw new Exception($message);
    }
    public static function isObject($object, $message = null)
    {
        if (!is_object($object))
            throw new Exception(
                $message.' not object given'
            );
    }

    public static function checkInteger($value)
    {
        return (
            is_numeric($value)
            && ($value == (int) $value)
            && (strlen($value) == strlen((int) $value))
        );
    }
    public static function checkFloat($value)
    {
        return (
            is_numeric($value)
            && ($value == (float) $value)
        );
    }
    public static function checkScalar($value)
    {
        return is_scalar($value);
    }
    public static function dumpArgument($argument)
    {
        return 'argument: ['.print_r($argument, true).']';
    }
    public static function dumpOppositeArguments($first, $second)
    {
        return
            'arguments: ['.print_r($first, true).'] '
            .'vs. ['.print_r($second, true).'] ';
    }
}