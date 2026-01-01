<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2026 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/environment-variables
 */

namespace Ergebnis\Environment\Test\Unit\Exception;

use Ergebnis\Environment\Exception;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Exception\ShouldNotBeUsed::class)]
final class ShouldNotBeUsedTest extends Framework\TestCase
{
    public function testCreateReturnsException(): void
    {
        $method = __METHOD__;

        $exception = Exception\ShouldNotBeUsed::create($method);

        $message = \sprintf(
            'Method "%s" should not be called.',
            $method,
        );

        self::assertSame($message, $exception->getMessage());
    }
}
