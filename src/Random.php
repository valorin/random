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

    public static function string(int $length = 32, $lower = true, $upper = true, $numbers = true, $symbols = true, bool $requireAll = false): string
    {
        $symbolMap = [
            '!', '"', '#', '$', '%', '&', '\'', '(', ')', '*', '+', ',', '-', '.', '/', ':',
            ';', '<', '=', '>', '?', '@', '[', '\\', ']', '^', '_', '`', '{', '|', '}', '~',
        ];

        $chars = array_filter([
            $lower ? range('a', 'z') : [],
            $upper ? range('A', 'Z') : [],
            $numbers ? range(0, 9) : [],
            $symbols ? $symbolMap : [],
        ]);

        $string = '';

        if ($requireAll) {
            foreach ($chars as $set) {
                $string .= $set[self::number(0, count($set) - 1)];
            }
        }

        $chars = array_merge(...$chars);

        while (strlen($string) < $length) {
            $string .= $chars[self::number(0, count($chars) - 1)];
        }

        if ($requireAll) {
            $string = self::shuffle($string);
        }

        return $string;
    }

    public static function letters(int $length = 32)
    {
        return self::string($length, true, true, false, false, false);
    }

    public static function token(int $length = 32)
    {
        return self::string($length, true, true, true, false, false);
    }

    public static function password(int $length = 32)
    {
        return self::string($length, true, true, true, true, false);

    }

    public static function shuffle($value)
    {
        if (is_string($value)) {
            return self::randomizer()->shuffleBytes($value);
        }

        if (is_array($value)) {
            return self::randomizer()->shuffleArray($value);
        }

        throw new \InvalidArgumentException('$value must be a string or an array');
    }

    protected static function randomizer(): Randomizer
    {
        if (! self::$randomizer) {
            self::$randomizer = new Randomizer;
        }

        return self::$randomizer;
    }
}
