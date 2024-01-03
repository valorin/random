<?php

namespace Tests;

trait Assertions
{
    protected function assertRegExpCustom($expression, $string, $message = '')
    {
        if (method_exists($this, 'assertMatchesRegularExpression')) {
            return $this->assertMatchesRegularExpression($expression, $string, $message);
        }

        $this->assertRegExp($expression, $string, $message);
    }
}
