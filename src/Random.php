<?php

namespace Valorin\Random;

use Random\Randomizer;

class Random
{
    protected static $randomizer;

    /**
     * Generate a random number between $min and $max.
     */
    public static function number(int $min, int $max): int
    {
        return self::randomizer()->getInt($min, $max);
    }

    protected static function randomizer(): Randomizer
    {
        if (! self::$randomizer) {
            self::$randomizer = new Randomizer;
        }

        return self::$randomizer;
    }
}
