<?php

namespace Tests;

use PHPUnit\Framework\Assert;

trait Helper
{
    protected function assertRegExpCustom($expression, $string, $message = '')
    {
        if (method_exists(parent::class, 'assertMatchesRegularExpression')) {
            return parent::assertMatchesRegularExpression($expression, $string, $message);
        }

        return $this->assertRegExp($expression, $string, $message);
    }
}
