<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Valorin\Random\Random;

class NumberTest extends TestCase
{
    public function testRandomNumbers()
    {
        for ($i = 0; $i < 10; $i++) {
            $number = Random::number(1, 100000);

            $this->assertIsInt($number);
            $this->assertGreaterThanOrEqual(1, $number);
            $this->assertLessThanOrEqual(100000, $number);
            $this->assertNotEquals(Random::number(1, 100000), $number);
        }
    }
}
