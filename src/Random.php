<?php

namespace Valorin\Random;

use Illuminate\Support\Collection;
use Random\Randomizer;

class Random
{
    /** @var \Random\Randomizer */
    protected static $randomizer;

    /**
     * Generate a random number between $min and $max, inclusive.
     *
     * @param  int  $min
     * @param  int  $max
     * @return int
     */
    public static function number(int $min, int $max): int
    {
        return self::randomizer()->getInt($min, $max);
    }

    /**
     * Generate a random string of $length characters, following the specified character rules.
     * The character sets can be overridden by passing an array of characters instead of a boolean.
     * If $requireAll is true, at least one character from each set will be included.
     *
     * @param  int         $length
     * @param  bool|array  $lower       If true, lowercase letters will be included. If an array, the array will be used as the character set.
     * @param  bool|array  $upper       If true, uppercase letters will be included. If an array, the array will be used as the character set.
     * @param  bool|array  $numbers     If true, numbers will be included. If an array, the array will be used as the character set.
     * @param  bool|array  $symbols     If true, symbols will be included. If an array, the array will be used as the character set.
     * @param  bool        $requireAll  If true, at least one character from each set will be included.
     * @return string
     */
    public static function string(int $length = 32, $lower = true, $upper = true, $numbers = true, $symbols = true, bool $requireAll = false): string
    {
        $symbolMap = [
            '!', '"', '#', '$', '%', '&', '\'', '(', ')', '*', '+', ',', '-', '.', '/', ':',
            ';', '<', '=', '>', '?', '@', '[', '\\', ']', '^', '_', '`', '{', '|', '}', '~',
        ];

        $chars = array_filter([
            is_array($lower) ? $lower : ($lower ? range('a', 'z') : []),
            is_array($upper) ? $upper : ($upper ? range('A', 'Z') : []),
            is_array($numbers) ? $numbers : ($numbers ? range(0, 9) : []),
            is_array($symbols) ? $symbols : ($symbols ? $symbolMap : []),
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
     * @param  int  $length
     * @return string
     */
    public static function otp(int $length = 6): string
    {
        return self::string($length, $lower = false, $upper = false, $numbers = true, $symbols = false, $requireAll = false);
    }

    /**
     * Generate a random string of $length lowercase and uppercase letters.
     *
     * @param  int  $length
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
     * @param  int  $length
     * @return string
     */
    public static function token(int $length = 32): string
    {
        return self::string($length, true, true, true, false, true);
    }

    /**
     * Generate a random password string of $length which includes lowercase and uppercase letters, numbers, and symbols.
     * Note, this doesn't guarantee that all the character sets will be included, you can use Random::string() for that.
     *
     * @param  int   $length
     * @param  bool  $requireAll If true, at least one character from each set will be included.
     * @return string
     */
    public static function password(int $length = 32, bool $requireAll = false): string
    {
        return self::string($length, $lower = true, $upper = true, $numbers = true, $symbols = true, $requireAll);
    }

    /**
     * Shuffle the characters in a string, array, or Laravel Collection,
     * optionally preserving the keys.
     *
     * @param  string|array|\Illuminate\Support\Collection  $values
     * @param  bool                                         $preserveKeys
     * @return string|array|\Illuminate\Support\Collection
     */
    public static function shuffle($values, bool $preserveKeys = false)
    {
        if (is_string($values)) {
            return self::randomizer()->shuffleBytes($values);
        }

        if ($values instanceof Collection) {
            $shuffled = self::shuffle($values->toArray(), $preserveKeys);
            $class = get_class($values);
            return new $class($shuffled);
        }

        if (! is_array($values)) {
            throw new \InvalidArgumentException('$value must be a string, array, or \Illuminate\Support\Collection.');
        }

        if (! $preserveKeys) {
            return self::randomizer()->shuffleArray($values);
        }

        $shuffledKeys = self::randomizer()->shuffleArray(array_keys($values));

        return array_reduce($shuffledKeys, function ($carry, $key) use ($values) {
            $carry[$key] = $values[$key];
            return $carry;
        }, []);
    }

    protected static function randomizer(): Randomizer
    {
        if (! self::$randomizer) {
            self::$randomizer = new Randomizer;
        }

        return self::$randomizer;
    }
}
