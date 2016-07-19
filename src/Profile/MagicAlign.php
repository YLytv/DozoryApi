<?php
namespace DozoryApi\Profile;

final class MagicAlign extends BaseEnum
{
    const Light     = 'light';
    const Dark      = 'dark';
    const Unknown   = 'unknown';

    protected static $list = [
        self::Light,
        self::Dark,
        self::Unknown,
    ];
}