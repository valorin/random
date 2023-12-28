<?php

namespace Valorin\Random;

use Random\Randomizer;

class Random
{
    protected static Randomizer $randomizer;

    /**
     * Generate a random number between $min and $max.
     */
    public static function number(int $min, int $max): int
    {
        return self::randomizer(
            function (Randomizer $randomizer) use ($min, $max) {
                return $randomizer->getInt($min, $max);
            },
            function () use ($min, $max) {
                return random_int($min, $max);
            }
        );
    }

    protected static function randomizer(callable $exists, callable $fallback)
    {
        return class_exists(Randomizer::class)
            ? $exists(self::$randomizer ??= new Randomizer)
            : $fallback();
    }
}
