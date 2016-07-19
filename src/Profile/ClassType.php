<?php
namespace DozoryApi\Profile;

final class ClassType extends BaseEnum
{
    const Wizard     = 1;
    const Witch      = 2;
    const Werewolf   = 3;
    const Vampire    = 4;
    const Incubus    = 5;

    protected static $list = [
        self::Wizard,
        self::Witch,
        self::Werewolf,
        self::Vampire,
        self::Incubus,
    ];
}