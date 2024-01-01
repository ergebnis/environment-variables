<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2024 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/environment-variables
 */

namespace Ergebnis\Environment\Test\Unit\Exception;

use Ergebnis\Environment\Exception;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Exception\InvalidValue::class)]
final class InvalidValueTest extends Framework\TestCase
{
    public function testCreateReturnsException(): void
    {
        $exception = Exception\InvalidValue::create();

        self::assertSame('Value needs to be a string or false.', $exception->getMessage());
    }
}
