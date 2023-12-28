<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Valorin\Random\Random;

class OtpTest extends TestCase
{
    public function testNumeric()
    {
        for ($i = 0; $i < 10; $i++) {
            $otp = Random::otp(6);
            $this->assertIsString($otp);
            $this->assertIsNumeric($otp);
        }
    }

    public function testLength()
    {
        for ($i = 0; $i < 10; $i++) {
            $otp = Random::otp(6);

            $this->assertEquals(6, strlen($otp));
        }
    }

    public function testDefaultPrefix()
    {
        $valid = false;
        for ($i = 0; $i < 100; $i++) {
            $otp = Random::otp(3, '0');

            $this->assertIsString($otp);
            $valid = $valid || substr($otp, 0, 1) === '0';
        }

        $this->assertTrue($valid, 'Prefix "z" was never seen in the results. (Low chance of a false positive.)');
    }

    public function testCustomPrefix()
    {
        $valid = false;
        for ($i = 0; $i < 100; $i++) {
            $otp = Random::otp(3, 'z');

            $valid = $valid || substr($otp, 0, 1) === 'z';
        }

        $this->assertTrue($valid, 'Prefix "z" was never seen in the results. (Low chance of a false positive.)');
    }
}
