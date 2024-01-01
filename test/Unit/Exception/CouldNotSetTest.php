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
use Ergebnis\Environment\Test;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Exception\CouldNotSet::class)]
final class CouldNotSetTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testNameReturnsException(): void
    {
        $name = self::faker()->word();

        $exception = Exception\CouldNotSet::name($name);

        $message = \sprintf(
            'Could not set environment variable "%s".',
            $name,
        );

        self::assertSame($message, $exception->getMessage());
    }
}
