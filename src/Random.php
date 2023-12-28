<?php

namespace Valorin\Random;

use Random\Randomizer;

class Random
{
    /** @var \Random\Randomizer */
    protected static $randomizer;

    /**
     * Generate a random number between $min and $max, inclusive.
     *
     * @param int $min
     * @param int $max
     * @return int
     */
    public static function number(int $min, int $max): int
    {
        return self::randomizer()->getInt($min, $max);
    }

    /**
     * Generate a random string of $length characters, following the specified character rules.
     *
     * @param int $length
     * @param bool $lower
     * @param bool $upper
     * @param bool $numbers
     * @param bool $symbols
     * @param bool $requireAll  If true, at least one character from each set will be included.
     * @return string
     */
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

    /**
     * Generate a numeric One-Time Password (OTP) of $length digits, suitable for use in 2FA.
     * Leading zeros will be included, so the output is a string rather than an integer.
     *
     * @param int $length
     * @return string
     */
    public static function otp(int $length = 6): string
    {
        return self::string($length, $lower = false, $upper = false, $numbers = true, $symbols = false, $requireAll = false);
    }

    /**
     * Generate a random string of $length lowercase and uppercase letters.
     *
     * @param int $length
     * @return string
     */
    public static function letters(int $length = 32): string
    {
        return self::string($length, $lower = true, $upper = true, $numbers = false, $symbols = false, $requireAll = false);
    }

    /**
     * Generate a random string of $length which includes lowercase and uppercase letters, and numbers.
     * This is suitable for use as a random token with sufficient length, and should have a near-zero chance of collisions.
     *
     * @param int $length
     * @return string
     */
    public static function token(int $length = 32): string
    {
        return self::string($length, true, true, true, false, false);
    }

    /**
     * Generate a random password string of $length which includes lowercase and uppercase letters, numbers, and symbols.
     * Note, this doesn't guarantee that all the character sets will be included, you can use Random::string() for that.
     *
     * @param int $length
     * @return string
     */
    public static function password(int $length = 32): string
    {
        return self::string($length, $lower = true, $upper = true, $numbers = true, $symbols = true, $requireAll = false);

    }

    public static function shuffle($values)
    {
        if (is_string($values)) {
            return self::randomizer()->shuffleBytes($values);
        }

        if (is_array($values)) {
            return self::randomizer()->shuffleArray($values);
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
