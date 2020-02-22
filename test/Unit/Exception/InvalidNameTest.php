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

use Ergebnis\Environment\Variables\Exception\InvalidName;
use Ergebnis\Test\Util\Helper;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\Environment\Variables\Exception\InvalidName
 */
final class InvalidNameTest extends Framework\TestCase
{
    use Helper;

    public function testCreateReturnsException(): void
    {
        $exception = InvalidName::create();

        self::assertSame('Name needs to be a non-empty, trimmed string.', $exception->getMessage());
    }
}
