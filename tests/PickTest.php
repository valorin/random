<?php

namespace Tests;

use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;
use Valorin\Random\Random;

class PickTest extends TestCase
    {
    use Assertions;

    public function testCantPickZeroElementFromArray()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Can not pick less than one item.');

        Random::pick(range('a', 'z'), 0);
    }

    public function testCantPickLessThanZeroElementFromArray()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Can not pick less than one item.');

        Random::pick(range('a', 'z'), -1);
    }

    public function testCantPickMoreThanArrayElements()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Can not pick more than existing elements.');

        Random::pick(range('a', 'z'), 27);
    }

    public function testCantPickZeroElementFromCollection()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Can not pick less than one item.');

        $collection = new Collection(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i']);
        Random::pick($collection, 0);
    }

    public function testCantPickLessThanZeroElementFromCollection()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Can not pick less than one item.');

        $collection = new Collection(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i']);
        Random::pick($collection, -1);
    }

    public function testCantPickMoreThanCollectionElements()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Can not pick more than existing elements.');

        $collection = new Collection(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i']);
        Random::pick($collection, 10);
    }

    public function testPickSingleFromArray()
    {
        $array = range('a', 'z');
        $picked = Random::pick($array, 1);

        $this->assertIsArray($picked);
        $this->assertCount(1, $picked);
        $this->assertContains($picked[0], $array);
        $this->assertNotSame(Random::pick($array, 1), $picked, 'Picks should be different. (Low chance of false positives.)');
    }

    public function testPickMultipleFromArray()
    {
        $array = range('a', 'z');
        $picked = Random::pick($array, 3);

        $this->assertIsArray($picked);
        $this->assertCount(3, $picked);
        $this->assertNotSame(Random::pick($array, 3), $picked, 'Picks should be different. (Low chance of false positives.)');
    }

    public function testPickSingleFromCollection()
    {
        $collection = new Collection(range('a', 'z'));
        $picked = Random::pick($collection, 1);

        $this->assertInstanceOf(Collection::class, $picked);
        $this->assertCount(1, $picked);
        $this->assertNotSame(Random::pick($collection, 1)->toArray(), $picked->toArray(), 'Picks should be different. (Low chance of false positives.)');
    }

    public function testPickMultipleFromCollection()
    {
        $collection = new Collection(range('a', 'z'));
        $picked = Random::pick($collection, 3);

        $this->assertInstanceOf(Collection::class, $picked);
        $this->assertCount(3, $picked);
        $this->assertNotSame(Random::pick($collection, 3)->toArray(), $picked->toArray(), 'Picks should be different. (Low chance of false positives.)');
    }

    public function testPickOneFromArray()
    {
        $array = range('a', 'z');
        $picked = Random::pickOne($array);

        $this->assertIsString($picked);
        $this->assertContains($picked, $array);
        $this->assertNotSame(Random::pickOne($array), $picked, 'Picks should be different. (Low chance of false positives.)');
    }

    public function testPickOneFromString()
    {
        $string = 'abcdefghijklmnopqrstuvwxyz';
        $picked = Random::pickOne($string);

        $this->assertIsString($picked);
        $this->assertEquals(1, strlen($picked));
        $this->assertStringContainsString($picked, $string);
        $this->assertNotSame(Random::pickOne($string), $picked, 'Picks should be different. (Low chance of false positives.)');
    }

    public function testPickOneFromCollection()
    {
        $collection = new Collection(range('a', 'z'));
        $picked = Random::pickOne($collection);

        $this->assertIsString($picked);
        $this->assertTrue($collection->contains($picked));
        $this->assertNotSame(Random::pickOne($collection), $picked, 'Picks should be different. (Low chance of false positives.)');
    }
}
