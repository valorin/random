<p align="center">
<a href="https://github.com/valorin/random/actions/workflows/php.yml"><img src="https://github.com/valorin/random/actions/workflows/php.yml/badge.svg"></a>
<a href="https://packagist.org/packages/valorin/random"><img src="https://img.shields.io/packagist/dt/valorin/random" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/valorin/random"><img src="https://img.shields.io/packagist/v/valorin/random" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/valorin/random"><img src="https://img.shields.io/packagist/l/valorin/random" alt="License"></a>
</p>

# Random by Valorin

Random is a simple helper package designed to make it easy to generate a range of different cryptographically secure 
random values in PHP apps.

Random was created because I was constantly encountering weak and insecure random value generations within apps during 
my [Laravel and PHP Security Audits](https://valorinsecurity.com/) and I wanted a secure solution to point my clients to
without needing them to implement secure algorithms themselves. The idea was then expanded out a bit to support all of 
the common random value types I've encountered.

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
    bool|array $lower = true, 
    bool|array $upper = true, 
    bool|array $numbers = true,
    bool|array $symbols = false, 
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

To specify the character types to use, you can pass an array of characters to use for each type:

```php
$string = Random::string(
    int $length = 32, 
    array $lower = ['a', 'b', 'c'], 
    array $upper = ['A', 'B', 'C'], 
    array $numbers = [1, 2, 3],
    array $symbols = ['!', '@', '#'], 
    bool $requireAll = false
): string;
```

### Shuffle

Securely shuffle a string, array, or Laravel Collection, optionally preserving the keys.

```php
$shuffled = Random::shuffle(
    string|array|\Illuminate\Support\Collection $values,
    bool $preserveKeys = false
): string|array|\Illuminate\Support\Collection;
```

### TODO

```php
Random::shuffle(array|collection)
Random::select($count)
Random::use(randomizer);
Random::with(randomizer)->number()
```

## Contributing

Contributions are very welcome! There isn't a formal guide, but throw in an Issue or PR and we'll go from there.

## Security Vulnerabilities

Please report any security vulnerabilities via the [GitHub project](https://github.com/valorin/random) 
or by contacting [Stephen Rees-Carter directly](https://stephenreescarter.net/.well-known/security.txt). 

## License

Random is open-source software licensed under the [MIT license](LICENSE.md).
