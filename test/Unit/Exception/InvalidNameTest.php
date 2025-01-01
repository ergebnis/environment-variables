<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2025 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/environment-variables
 */

namespace Ergebnis\Environment\Test\Unit\Exception;

use Ergebnis\Environment\Exception;
use Ergebnis\Environment\Test;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Exception\InvalidName::class)]
final class InvalidNameTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testCreateReturnsException(): void
    {
        $exception = Exception\InvalidName::create();

        self::assertSame('Name needs to be a non-empty, trimmed string.', $exception->getMessage());
    }
}
