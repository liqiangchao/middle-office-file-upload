<?php

namespace Lqc\MiddleOfficeFileUpload\Utils;

class Utils
{
    /**
     * Parse it by JSON format.
     *
     * @param  string  $jsonString
     * @return array the parsed result
     */
    public static function parseJSON($jsonString): array
    {
        return json_decode($jsonString, true);
    }


    /**
     * If not set the real, use default value.
     *
     * @param  array  $object
     * @return string the return string
     */
    public static function toJSONString($object): string
    {
        if (is_string($object)) {
            return $object;
        }

        return json_encode($object, JSON_UNESCAPED_UNICODE + JSON_UNESCAPED_SLASHES);
    }

    /**
     * Check the string is empty?
     *
     * @param string $val
     * @return bool if string is null or zero length, return true
     */
    public static function empty_(string $val): bool
    {
        return empty($val);
    }

    /**
     * Check one value is unset.
     *
     * @param mixed|null $value
     * @return bool if unset, return true
     */
    public static function isUnset(mixed $value = null): bool
    {
        return !isset($value);
    }

    /**
     * Assert a value, if it is a string, return it, otherwise throws.
     *
     * @param  mixed  $value
     * @return string the string value
     */
    public static function assertAsString(mixed $value): string
    {
        if (\is_string($value)) {
            return $value;
        }

        throw new \InvalidArgumentException('It is not a string value.');
    }

    /**
     * Assert a value, if it is a number, return it, otherwise throws.
     *
     * @param mixed $value
     * @return float|int|string
     */
    public static function assertAsNumber(mixed $value): float|int|string
    {
        if (\is_numeric($value)) {
            return $value;
        }

        throw new \InvalidArgumentException('It is not a number value.');
    }

    /**
     * 断言为数组
     *
     * @param $any
     * @return array
     * @author Wumeng wumeng@gupo.onaliyun.com
     * @since 2023-07-03 17:55
     */
    public static function assertAsArray($any): array
    {
        if (\is_array($any)) {
            return $any;
        }

        throw new \InvalidArgumentException('It is not a array value.');
    }

}