<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2022 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/environment-variables
 */

namespace Ergebnis\Environment\Test\Unit\Exception;

use Ergebnis\Environment\Exception\CouldNotSet;
use Ergebnis\Environment\Test;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\Environment\Exception\CouldNotSet
 */
final class CouldNotSetTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testNameReturnsException(): void
    {
        $name = self::faker()->word();

        $exception = CouldNotSet::name($name);

        $message = \sprintf(
            'Could not set environment variable "%s".',
            $name,
        );

        self::assertSame($message, $exception->getMessage());
    }
}
