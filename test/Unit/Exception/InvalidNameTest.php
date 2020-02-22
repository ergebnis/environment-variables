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
        $name = \sprintf(
            ' %s ',
            self::faker()->word
        );

        $exception = InvalidName::create($name);

        $message = \sprintf(
            'Name cannot be empty or untrimmed, but "%s" is.',
            $name
        );

        self::assertSame($message, $exception->getMessage());
    }
}
