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

namespace Ergebnis\Environment\Exception;

final class ShouldNotBeUsed extends \BadMethodCallException implements Exception
{
    public static function create(string $method): self
    {
        return new self(\sprintf(
            'Method "%s" should not be called.',
            $method,
        ));
    }
}
