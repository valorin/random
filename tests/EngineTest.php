<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Random\Engine\Mt19937;
use Valorin\Random\Random;

class EngineTest extends TestCase
{
    public function testSeededEngineIsntGlobal()
    {
        $generator = Random::use(new Mt19937(3791));

        $this->assertNotEquals(14065, Random::number(1, 100000), 'The random generator shouldn\'t pick the seeded value (but it may occasionally).');
        $this->assertEquals(14065, $generator->number(1, 100000));

        $this->assertNotEquals(847994, Random::otp(), 'The random generator shouldn\'t pick the seeded value (but it may occasionally).');
        $this->assertEquals(847994, $generator->otp());

        $this->assertNotEquals('hw8kXvG060UyLKq8oKyVyXsmPC5ED9pa', Random::token(), 'The random generator shouldn\'t pick the seeded value (but it may occasionally).');
        $this->assertEquals('hw8kXvG060UyLKq8oKyVyXsmPC5ED9pa', $generator->token());
    }

    public function testSeededEngineIsUnique()
    {
        $generatorOne = Random::use(new Mt19937(1));
        $generatorTwo = Random::use(new Mt19937(2));

        $tokenOne = $generatorOne->token();
        $tokenTwo = $generatorTwo->token();

        $this->assertNotEquals($tokenOne, $tokenTwo);
    }
}
