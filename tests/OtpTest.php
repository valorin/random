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
        for ($length = 3; $length < 13; $length++) {
            $otp = Random::otp($length);

            $this->assertEquals($length, strlen($otp));
        }
    }

    public function testLeadingZerosAreSupported()
    {
        $valid = false;
        for ($i = 0; $i < 100; $i++) {
            $otp = Random::otp(3);

            $this->assertIsString($otp);
            $this->assertEquals(3, strlen($otp));
            $valid = $valid || substr($otp, 0, 1) === '0';
        }

        $this->assertTrue($valid, 'Leading Zero was not seen in the results. (Low chance of a false positive.)');
    }
}
