<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Valorin\Random\Random;

class NumberTest extends TestCase
{
    public function testNumbersWithinLimits()
    {
        for ($i = 0; $i < 10; $i++) {
            $number = Random::number(1, 100);

            $this->assertIsInt($number);
            $this->assertGreaterThanOrEqual(1, $number);
            $this->assertLessThanOrEqual(100, $number);
        }
    }

    public function testDifferentNumbers()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->assertNotEquals(
                Random::number(1, 100000),
                Random::number(1, 100000)
            );
        }
    }
}
