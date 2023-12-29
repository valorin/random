<?php

namespace Tests;

use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use Valorin\Random\Random;

class ShuffleTest extends TestCase
{
    public function testShuffleString()
    {
        for ($i = 0; $i < 10; $i++) {
            $string = 'original';
            $shuffled = Random::shuffle($string);

            $this->assertIsString($shuffled);
            $this->assertEquals(strlen($string), strlen($shuffled));
            $this->assertNotSame($string, $shuffled);
            $this->assertNotSame(Random::shuffle($string), $shuffled);
        }
    }

    public function testShuffleArrayWithoutPreservingKeys()
    {
        for ($i = 0; $i < 10; $i++) {
            $array = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i'];
            $shuffled = Random::shuffle($array, $preserveKeys = false);

            $this->assertIsArray($shuffled);
            $this->assertEquals(count($array), count($shuffled));
            $this->assertNotSame($array, $shuffled);
            $this->assertNotSame(Random::shuffle($array), $shuffled);
            $this->assertSame(range(0, 8), array_keys($shuffled), 'Keys were not re-indexed.');
        }
    }

    public function testShuffleArrayPreservingKeys()
    {
        for ($i = 0; $i < 10; $i++) {
            $array = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i'];
            $shuffled = Random::shuffle($array, $preserveKeys = true);

            $this->assertNotSame($array, $shuffled);
            $this->assertNotSame(Random::shuffle($array), $shuffled);
            $this->assertNotSame(range(0, 8), array_keys($shuffled), 'Keys were re-indexed.');

            for ($j = 0; $j < 9; $j++) {
                $this->assertSame($array[$j], $shuffled[$j], "Key {$j} was not preserved.");
            }
        }
    }

    public function testShuffleCollectionWithoutPreservingKey()
    {
        for ($i = 0; $i < 10; $i++) {
            $collection = new Collection(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i']);
            $shuffled = Random::shuffle($collection, $preserveKeys = false);

            $this->assertInstanceOf(Collection::class, $shuffled);
            $this->assertNotSame($collection->toArray(), $shuffled->toArray());
            $this->assertNotSame(Random::shuffle($collection)->toArray(), $shuffled->toArray());
            $this->assertSame(range(0, 8), $shuffled->keys()->toArray(), 'Keys were not re-indexed.');
        }
    }

    public function testShuffleCollectionPreservingKey()
    {
        for ($i = 0; $i < 10; $i++) {
            $collection = new Collection(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i']);
            $shuffled = Random::shuffle($collection, $preserveKeys = true);

            $this->assertInstanceOf(Collection::class, $shuffled);
            $this->assertNotSame($collection->toArray(), $shuffled->toArray());
            $this->assertNotSame(Random::shuffle($collection)->toArray(), $shuffled->toArray());
            $this->assertNotSame(range(0, 8), $shuffled->keys(), 'Keys were re-indexed.');

            for ($j = 0; $j < 9; $j++) {
                $this->assertSame($collection[$j], $shuffled[$j], "Key {$j} was not preserved.");
            }
        }
    }
}
