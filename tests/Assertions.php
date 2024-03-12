<?php

namespace Tests;

trait Assertions
{
    protected function assertRegExpCustom($expression, $string, $message = ''): void
    {
        if (method_exists($this, 'assertMatchesRegularExpression')) {
            $this->assertMatchesRegularExpression($expression, $string, $message);

            return;
        }

        $this->assertRegExp($expression, $string, $message);
    }
}
