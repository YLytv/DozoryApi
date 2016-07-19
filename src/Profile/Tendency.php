<?php
namespace DozoryApi\Profile;


final class Tendency extends BaseEnum
{
    const Adept     = "1";
    const Free      = "0";
    const Outcast   = "-1";

    protected static $list = [
        self::Adept,
        self::Free,
        self::Outcast,
    ];
}