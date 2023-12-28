<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Valorin\Random\Random;

class StringTest extends TestCase
{
    public function testLength()
    {
        for ($length = 10; $length < 20; $length++) {
            $string = Random::string($length);

            $this->assertIsString($string);
            $this->assertEquals($length, strlen($string));
        }
    }

    public function testHasLowerCase()
    {
        $valid = false;
        for ($i = 0; $i < 10; $i++) {
            $string = Random::string();

            $valid = $valid || preg_match('/[a-z]/', $string);
        }

        $this->assertTrue($valid, 'Lowercase letters were never seen in the result. (Low chance of a false positive.)');
    }

    public function testNoLowerCase()
    {
        $valid = false;
        for ($i = 0; $i < 10; $i++) {
            $string = Random::string(
                $length = 32,
                $lower = false,
                $upper = true,
                $numbers = true,
                $symbols = true,
                $requireAll = false
            );

            $valid = $valid || preg_match('/[^a-z]/', $string);
        }

        $this->assertTrue($valid, 'Lowercase letters were never seen in the result. (Low chance of a false positive.)');
    }

    public function testHasNumbers()
    {
        $valid = false;
        for ($i = 0; $i < 10; $i++) {
            $string = Random::string();

            $valid = $valid || preg_match('/[0-9]/', $string);
        }

        $this->assertTrue($valid, 'Uppercase letters were never seen in the result. (Low chance of a false positive.)');
    }

    public function testNoNumbers()
    {
        $valid = false;
        for ($i = 0; $i < 10; $i++) {
            $string = Random::string(
                $length = 32,
                $lower = true,
                $upper = true,
                $numbers = false,
                $symbols = true,
                $requireAll = false
            );

            $valid = $valid || preg_match('/[^0-9]/', $string);
        }

        $this->assertTrue($valid, 'Uppercase letters were never seen in the result. (Low chance of a false positive.)');
    }

    public function testHasUpperCase()
    {
        $valid = false;
        for ($i = 0; $i < 10; $i++) {
            $string = Random::string();

            $valid = $valid || preg_match('/[A-Z]/', $string);
        }

        $this->assertTrue($valid, 'Uppercase letters were never seen in the result. (Low chance of a false positive.)');
    }

    public function testNoUpperCase()
    {
        $valid = false;
        for ($i = 0; $i < 10; $i++) {
            $string = Random::string(
                $length = 32,
                $lower = true,
                $upper = false,
                $numbers = true,
                $symbols = true,
                $requireAll = false
            );

            $valid = $valid || preg_match('/[^A-Z]/', $string);
        }

        $this->assertTrue($valid, 'Uppercase letters were never seen in the result. (Low chance of a false positive.)');
    }

    public function testHasSymbols()
    {
        $valid = false;
        for ($i = 0; $i < 10; $i++) {
            $string = Random::string();

            $valid = $valid || preg_match('/[^a-zA-Z0-9]/', $string);
        }

        $this->assertTrue($valid, 'Uppercase letters were never seen in the result. (Low chance of a false positive.)');
    }

    public function testNoSymbols()
    {
        $valid = false;
        for ($i = 0; $i < 10; $i++) {
            $string = Random::string(
                $length = 32,
                $lower = true,
                $upper = true,
                $numbers = true,
                $symbols = false,
                $requireAll = false
            );

            $valid = $valid || preg_match('/^[a-zA-Z0-9]+$/', $string);
        }

        $this->assertTrue($valid, 'Uppercase letters were never seen in the result. (Low chance of a false positive.)');
    }

    public function testRequireAll()
    {
        for ($i = 0; $i < 100; $i++) {
            $string = Random::string(
                $length = 32,
                $lower = true,
                $upper = true,
                $numbers = true,
                $symbols = true,
                $requireAll = true
            );

            $this->assertRegExpCustom('/[a-z]/', $string);
            $this->assertRegExpCustom('/[A-Z]/', $string);
            $this->assertRegExpCustom('/[0-9]/', $string);
            $this->assertRegExpCustom('/[^a-zA-Z0-9]/', $string);
        }
    }

    public function testLetters()
    {
        for ($i = 0; $i < 100; $i++) {
            $string = Random::letters();

            $this->assertIsString($string);
            $this->assertRegExpCustom('/^[a-zA-Z]+$/', $string);
        }
    }

    public function testToken()
    {
        for ($i = 0; $i < 100; $i++) {
            $string = Random::token();

            $this->assertIsString($string);
            $this->assertRegExpCustom('/^[a-zA-Z0-9]+$/', $string);
        }
    }

    public function testPassword()
    {
        $valid = false;

        for ($i = 0; $i < 10; $i++) {
            $string = Random::password();

            $valid = $valid || preg_match('/[a-z]/', $string);
            $valid = $valid || preg_match('/[0-9]/', $string);
            $valid = $valid || preg_match('/[A-Z]/', $string);
            $valid = $valid || preg_match('/[^a-zA-Z0-9]/', $string);
        }

        $this->assertTrue($valid, 'Not all character types were seen in the result. (Low chance of a false positive.)');
    }

    // @TODO Custom character sets

    protected function assertRegExpCustom($expression, $string, $message = '')
    {
        if (method_exists(parent::class, 'assertMatchesRegularExpression')) {
            return parent::assertMatchesRegularExpression($expression, $string, $message);
        }

        return $this->assertRegExp($expression, $string, $message);
    }
}
