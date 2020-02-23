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

namespace Ergebnis\Environment\Test\Unit\Exception;

use Ergebnis\Environment\Exception\InvalidValue;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\Environment\Exception\InvalidValue
 */
final class InvalidValueTest extends Framework\TestCase
{
    public function testCreateReturnsException(): void
    {
        $exception = InvalidValue::create();

        self::assertSame('Value needs to be a string or false.', $exception->getMessage());
    }
}
