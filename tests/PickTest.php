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
        for ($i = 0; $i < 10; $i++) {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('Can not pick less than one item.');

            Random::pick(range('a', 'z'), 0);
        }
    }

    public function testCantPickLessThanZeroElementFromArray()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('Can not pick less than one item.');

            Random::pick(range('a', 'z'), -1);
        }
    }

    public function testCantPickMoreThanArrayElements()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('Can not pick more than existing elements.');

            Random::pick(range('a', 'z'), 27);
        }
    }

    public function testCantPickZeroElementFromString()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('Can not pick less than one item.');

            Random::pick('abcdefghijklmnopqrstuvwxyz', 0);
        }
    }

    public function testCantPickLessThanZeroElementFromString()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('Can not pick less than one item.');

            Random::pick('abcdefghijklmnopqrstuvwxyz', -1);
        }
    }

    public function testCantPickMoreThanStringElements()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('Can not pick more than existing elements.');

            Random::pick('abcdefghijklmnopqrstuvwxyz', 27);
        }
    }

    public function testCantPickZeroElementFromCollection()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('Can not pick less than one item.');

            $collection = new Collection(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i']);
            Random::pick($collection, 0);
        }
    }

    public function testCantPickLessThanZeroElementFromCollection()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('Can not pick less than one item.');

            $collection = new Collection(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i']);
            Random::pick($collection, -1);
        }
    }

    public function testCantPickMoreThanCollectionElements()
    {
        for ($i = 0; $i < 10; $i++) {
            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('Can not pick more than existing elements.');

            $collection = new Collection(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i']);
            Random::pick($collection, 10);
        }
    }

    public function testPickSingleFromArray()
    {
        $differentPick = false;

        for ($i = 0; $i < 10; $i++) {
            $array = range('a', 'z');
            $picked = Random::pick($array, 1);

            $this->assertIsString($picked);
            $this->assertContains($picked, $array);

            $differentPick = $differentPick || $picked !== Random::pick($array, 1);
        }

        $this->assertTrue($differentPick);
    }

    public function testPickSingleFromString()
    {
        $differentPick = false;

        for ($i = 0; $i < 10; $i++) {
            $string = 'abcdefghijklmnopqrstuvwxyz';
            $picked = Random::pick($string, 1);

            $this->assertIsString($picked);
            $this->assertStringContainsString($picked, $string);

            $differentPick = $differentPick || $picked !== Random::pick($string, 1);
        }

        $this->assertTrue($differentPick);
    }

    public function testPickMultipleFromArray()
    {
        for ($i = 0; $i < 10; $i++) {
            $array = range('a', 'z');
            $picked = Random::pick($array, 3);

            $this->assertIsArray($picked);
            $this->assertCount(3, $picked);
            $this->assertNotSame(Random::pick($array, 3), $picked, 'Picks should be different. (Low chance of false positives.)');
        }
    }

    public function testPickMultipleFromString()
    {
        for ($i = 0; $i < 10; $i++) {
            $string = 'abcdefghijklmnopqrstuvwxyz';
            $picked = Random::pick($string, 3);

            $this->assertIsString($picked);
            $this->assertEquals(3, strlen($picked));
            $this->assertNotSame(Random::pick($string, 3), $picked, 'Picks should be different. (Low chance of false positives.)');
            $this->assertRegExpCustom('/^[a-z]+$/', $string);
        }
    }

    public function testPickSingleFromCollection()
    {
        $differentPick = false;

        for ($i = 0; $i < 10; $i++) {
            $collection = new Collection(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i']);
            $picked = Random::pick($collection, 1);

            $this->assertIsString($picked);
            $this->assertTrue($collection->contains($picked));

            $differentPick = $differentPick || $picked !== Random::pick($collection, 1);
        }

        $this->assertTrue($differentPick);
    }

    public function testPickMultipleFromCollection()
    {
        for ($i = 0; $i < 10; $i++) {
            $collection = new Collection(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i']);
            $picked = Random::pick($collection, 3);

            $this->assertInstanceOf(Collection::class, $picked);
            $this->assertCount(3, $picked);
            $this->assertNotSame(Random::pick($collection, 3)->toArray(), $picked->toArray(), 'Picks should be different. (Low chance of false positives.)');
        }
    }

    public function testPickOneFromArray()
    {
        $differentPick = false;

        for ($i = 0; $i < 10; $i++) {
            $array = range('a', 'z');
            $picked = Random::pickOne($array);

            $this->assertIsString($picked);
            $this->assertContains($picked, $array);

            $differentPick = $differentPick || $picked !== Random::pickOne($array);
        }

        $this->assertTrue($differentPick);
    }

    public function testPickOneFromString()
    {
        $differentPick = false;

        for ($i = 0; $i < 10; $i++) {
            $string = 'abcdefghijklmnopqrstuvwxyz';
            $picked = Random::pickOne($string);

            $this->assertIsString($picked);
            $this->assertStringContainsString($picked, $string);

            $differentPick = $differentPick || $picked !== Random::pickOne($string);
        }

        $this->assertTrue($differentPick);
    }

    public function testPickOneFromCollection()
    {
        $differentPick = false;

        for ($i = 0; $i < 10; $i++) {
            $collection = new Collection(['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i']);
            $picked = Random::pickOne($collection);

            $this->assertIsString($picked);
            $this->assertTrue($collection->contains($picked));

            $differentPick = $differentPick || $picked !== Random::pickOne($collection);
        }

        $this->assertTrue($differentPick);
    }
}
