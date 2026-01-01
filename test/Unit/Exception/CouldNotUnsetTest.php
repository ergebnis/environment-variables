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
use Ergebnis\Environment\Test;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(Exception\CouldNotUnset::class)]
final class CouldNotUnsetTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testNameReturnsException(): void
    {
        $name = self::faker()->word();

        $exception = Exception\CouldNotUnset::name($name);

        $message = \sprintf(
            'Could not unset environment variable "%s".',
            $name,
        );

        self::assertSame($message, $exception->getMessage());
    }
}
