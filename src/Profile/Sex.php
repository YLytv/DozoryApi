<?php
namespace DozoryApi\Profile;


final class Sex extends BaseEnum
{
    const Male     = 'm';
    const Female   = 'f';

    protected static $list = [
        self::Male,
        self::Female
    ];
}