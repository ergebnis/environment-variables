<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2021 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/environment-variables
 */

namespace Ergebnis\Environment\Test\Unit\Exception;

use Ergebnis\Environment\Exception\NotBackedUp;
use Ergebnis\Environment\Test;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\Environment\Exception\NotBackedUp
 */
final class NotBackedUpTest extends Framework\TestCase
{
    use Test\Util\Helper;

    public function testNamesReturnsException(): void
    {
        /** @var string[] $names */
        $names = self::faker()->words;

        $exception = NotBackedUp::names(...$names);

        $message = \sprintf(
            'The environment variables "%s" have not been backed up. Are you sure you want to modify them?',
            \implode('", "', $names),
        );

        self::assertSame($message, $exception->getMessage());
    }
}
