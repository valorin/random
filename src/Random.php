<?php

namespace Valorin\Random;

/**
 * @see \Valorin\Random\Generator
 *
 * @method static int number(int $min, int $max) Generate a random number between $min and $max, inclusive.
 * @method static string string(int $length = 32, bool $lower = true, bool $upper = true, bool $numbers = true, bool $symbols = true, bool $requireAll = false) Generate a random string of $length characters, following the specified character rules.
 * @method static string otp(int $length = 6) Generate a numeric One-Time Password (OTP) of $length digits, suitable for use in 2FA.
 * @method static string passcode(int $length = 6) Generate a numeric One-Time Password (OTP) of $length digits, suitable for use in 2FA.
 * @method static string letters(int $length = 32) Generate a random string of $length lowercase and uppercase letters.
 * @method static string token(int $length = 32) Generate a random string of $length which includes lowercase and uppercase letters, and numbers.
 * @method static string password(int $length = 16, bool $requireAll = false) Generate a random password string of $length which includes lowercase and uppercase letters, numbers, and symbols.
 * @method static string dashed(int $length = 25, string $delimiter = '-', int $chunkLength = 5, bool $mixedCase = true) Generate a random string of $length with lowercase and uppercase letters, and numbers, divided by $divider.
 * @method static array|string|\Illuminate\Support\Collection shuffle(array|string|\Illuminate\Support\Collection $values, bool $preserveKeys = false) Shuffle the characters in an array, string, or Laravel Collection, optionally preserving the keys.
 * @method static array|\Illuminate\Support\Collection pick(array|\Illuminate\Support\Collection $values, int $count) Pick $count random items (or characters) from an array or Laravel Collection.
 * @method static array|string|\Illuminate\Support\Collection single(array|string|\Illuminate\Support\Collection $values) Picks a single item (or character) from an array, string, or Laravel Collection.
 * @method static array|string|\Illuminate\Support\Collection pickOne(array|string|\Illuminate\Support\Collection $values) Picks a single item (or character) from an array, string, or Laravel Collection.
 * @method static Generator useLower(array $characters) Use custom lower case character set for random string generation.
 * @method static Generator useUpper(array $characters) Use custom upper case character set for random string generation.
 * @method static Generator useNumbers(array $characters) Use custom number characters for random string generation.
 * @method static Generator useSymbols(array $characters) Use custom symbol character set for random string generation.
 */
class Random
{
    /** @var Generator|null */
    protected static $generator;

    public static function use(\Random\Engine $engine): Generator
    {
        return new Generator($engine);
    }

    public static function __callStatic($name, $arguments)
    {
        $generator = strpos($name, 'use') === 0
            ? new Generator
            : static::generator();

        return $generator->{$name}(...$arguments);
    }

    protected static function generator(): Generator
    {
        return static::$generator ?? static::$generator = new Generator;
    }
}
