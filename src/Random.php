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

    public static function otp(int $length, string $prefix = '0'): string
    {
        return str_pad(
            self::number(0, (int) str_repeat('9', $length)),
            $length,
            $prefix,
            STR_PAD_LEFT
        );
    }

    protected static function randomizer(): Randomizer
    {
        if (! self::$randomizer) {
            self::$randomizer = new Randomizer;
        }

        return self::$randomizer;
    }
}
