<?php

use Valorin\Random\Random;

it('generates random numbers', function () {
    expect(Random::number(1, 1000))
        ->toBeInt()
        ->toBeGreaterThanOrEqual(1)
        ->toBeLessThanOrEqual(1000)
        ->not->toBe(Random::number(1, 1000));
})->repeat(10);
