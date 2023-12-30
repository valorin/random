<p align="center">
<a href="https://github.com/valorin/random/actions/workflows/php.yml"><img src="https://github.com/valorin/random/actions/workflows/php.yml/badge.svg"></a>
<a href="https://packagist.org/packages/valorin/random"><img src="https://img.shields.io/packagist/dt/valorin/random" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/valorin/random"><img src="https://img.shields.io/packagist/v/valorin/random" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/valorin/random"><img src="https://img.shields.io/packagist/l/valorin/random" alt="License"></a>
</p>

# `Random` by [Valorin](https://stephenreescarter.net/)

Random is a simple helper package designed to make it easy to generate a range of different cryptographically secure 
random values in PHP apps.

Random was created because I was constantly encountering weak and insecure random value generations within apps during 
my [Laravel and PHP Security Audits](https://valorinsecurity.com/) and I wanted a secure solution to point my clients to
without needing them to implement secure algorithms themselves. The idea was then expanded out a bit to support all 
the common random value types I've encountered.

Random is completely framework agnostic, the only production dependency is the excellent [php-random-polyfill](https://github.com/arokettu/php-random-polyfill),
which does nothing on PHP 8.2+ where the functions are included in core. It supports Laravel's Collections, but doesn't pull in any Laravel code.

## Installation

You can install the package via composer:

```bash
composer require valorin/random
```

There is no need to install any service providers, Random should just work out of the box.

Random is supported on PHP 7.1 and later. 

## Usage

Random is designed to be as simple as possible to use. It's a static class, so you can just call the methods directly.

Import the class into your namespace:

```php
use Valorin\Random\Random;
```

### Random Integers

Generate a random integer between `$min`, and `$max` (inclusive):

```php
$number = Random::number(int $min, int $max): int;
```

Note, this is only really useful if you're using a specific 
[Randomizer Engine](https://www.php.net/manual/en/book.random.php) (such as when using seeds).
For most use cases, I'd suggest sticking with `random_int()` for simplicity.

### Random One-Time Password (Numeric fixed-length OTPs)

Generate a random numeric one-time password (OTP) of `$length` digits:

```php
$otp = Random::otp(int $length): string;
```

This is useful for generating OTPs for SMS or email verification codes. These are commonly done using 
`rand(100000, 999999)`, which is both insecure and also loses 10% of the possible codes in the `0-99999` range. 
This provides a secure alternative which includes the full `000000-999999` range (with variable length).

### Random String

Generate a random string of `$length` characters which includes characters from the enabled character types.
By default, it will randomly select characters and not guarantee any specific character types are present.
If you require one of each character to be included, you can set `$requireAll = true`.

```php
// Primary method
$string = Random::string(
    int $length = 32,
    bool $lower = true,
    bool $upper = true,
    bool $numbers = true,
    bool $symbols = false,
    bool $requireAll = false
): string;
```

The following are wrappers for common use cases:

```php
// Random letters only
$string = Random::letters(int $length = 32): string;

// Random letters and numbers (i.e. a random token)
$string = Random::token(int $length = 32): string;

// Random letters, numbers, and symbols (i.e. a random password).
$string = Random::password(int $length = 32, bool $requireAll = false): string;
```

To limit the characters available in any of the types (i.e. lower, upper, numbers, or symbols),
you can create a custom Generator instance with your customer character set:

```php
// Override just symbols
$generator = Random::useSymbols(['!', '@', '#', '$', '%', '^', '&', '*', '(', ')'])->string();

// Override everything
$generator = Random::useLower(range('a', 'f'))
    ->useUpper(range('G', 'L'))
    ->useNumbers(range(2, 6))
    ->useSymbols(['!', '@', '#', '$', '%', '^', '&', '*', '(', ')']);

$string = $generator->string(
    $length = 32,
    $lower = true,
    $upper = true,
    $numbers = true,
    $symbols = true,
    $requireAll = true
);
```

Note, you can chain the `use*()` methods in any order, and they will persist within that Generator only.

### Shuffle Array, String, or Collection

Securely shuffle an array, string, or Laravel Collection, optionally preserving the keys.

```php
$shuffled = Random::shuffle(
    array|string|\Illuminate\Support\Collection $values,
    bool $preserveKeys = false
): array|string|\Illuminate\Support\Collection;
```

### Pick `$count` Items or Characters

Securely pick `$count` items (or characters) from an array, string, or Laravel Collection.

```php
$picks = Random::pick(
    array|string|\Illuminate\Support\Collection $values,
    int $count
): array|string|\Illuminate\Support\Collection;
```

Passing `$count > 1` will return the picks in the same type as `$values`, so either an array, a Collection,
or a string of characters.

When passing `$count = 1`, the output will be a single array/collection item or character chosen from the input.
You can also use the alias `pickOne()` to pick a single item from `$values`:

```php
$pick = Random::pick(
    array|string|\Illuminate\Support\Collection $values
): array|string|\Illuminate\Support\Collection;
```

### Using a specific `\Random\Engine`

By default `Random` will use the secure default `\Random\Engine` defined by PHP. 
To use a different Engine, pass it to the `use()` method and call the above methods on the returned
`Generator` class.

```php
$number = Random::use(\Random\Engine $engine): \Valorin\Random\Generator; 
```

The primary use case for `use()` is when you need to specify a specific random seed, in order to control the output.
Only the returned `\Valorin\Random\Generator` object will use the provided Engine (and seed), allowing you to
create and use the Generator independently of other uses of `Random` within your app.

```php
$generator = Random::use(new \Random\Engine\Mt19937(3791));

$number = $generator->number(1, 1000);
$password = $generator->password();
```

You can use `use()` alongside the character set helpers (`useLower()`, `useUpper()`, `useNumbers()`, `useSymbols()`),
although you will need to call `use()` first to define the Engine before customising the character set on the 
`Generator` object.

## Support My Work! ❤️

You can support my work over on [GitHub Sponsors](https://github.com/sponsors/valorin)
or by becomming a paid subscriber to [Securing Laravel](https://securinglaravel.com/), the essential security resource for 
Laravel and PHP developers!

## Contributing

Contributions are very welcome! There isn't a formal guide, but throw in an Issue or PR and we'll go from there.

## Security Vulnerabilities

Please report any security vulnerabilities via the [GitHub project](https://github.com/valorin/random) 
or by contacting [Stephen Rees-Carter directly](https://stephenreescarter.net/.well-known/security.txt). 

## License

Random is open-source software licensed under the [MIT license](LICENSE.md).
