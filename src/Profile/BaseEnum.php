<?php
namespace DozoryApi\Profile;


abstract class BaseEnum
{
    protected static $list = [
    ];

    public static function get($type)
    {
        $result = null;

        $type = strtolower($type);

        if (in_array($type, static::$list)){
            $result = $type;
        }

        return $result;
    }
}