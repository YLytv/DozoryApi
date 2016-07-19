<?php

namespace DozoryApi;

use InvalidArgumentException;
use PHPUnit_Framework_TestCase;

class ProfileTest extends PHPUnit_Framework_TestCase
{
    public function testIncorrectArgumentLoad()
    {
        try {
            Profile::load("123");
        } finally {
            $this->expectException(InvalidArgumentException::class);
        }

    }

    public function testLargeArgumentLoad()
    {
        $items = [];
        for ($i = 0; $i <= Profile::MAX_COUNT; $i++) {
            $items[] = $i;
        }

        try {
            Profile::load($items);
        } finally {
            $this->expectException(InvalidArgumentException::class);
        }

    }

    public function testLoad()
    {
        $items = [248, 249, 252];
        $profiles = Profile::load($items);

        $this->assertEquals(count($profiles), count($items));
    }

}
