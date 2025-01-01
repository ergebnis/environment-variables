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

#[Framework\Attributes\CoversClass(Exception\NotSet::class)]
final class NotSetTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testNameReturnsException(): void
    {
        $name = self::faker()->word();

        $exception = Exception\NotSet::name($name);

        $message = \sprintf(
            'The environment variable "%s" is not set.',
            $name,
        );

        self::assertSame($message, $exception->getMessage());
    }
}
