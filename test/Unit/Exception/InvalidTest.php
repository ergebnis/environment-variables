<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/environment-variables
 */

namespace Ergebnis\Environment\Variables\Test\Unit\Exception;

use Ergebnis\Environment\Variables\Exception\Invalid;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\Environment\Variables\Exception\Invalid
 */
final class InvalidTest extends Framework\TestCase
{
    public function testNamesReturnsException(): void
    {
        $exception = Invalid::names();

        self::assertSame('Keys need to be string values and cannot be empty or untrimmed.', $exception->getMessage());
    }

    public function testValuesReturnsException(): void
    {
        $exception = Invalid::values();

        self::assertSame('Values need to be either string values or false.', $exception->getMessage());
    }
}
