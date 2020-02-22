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

namespace Ergebnis\Environment\Variables\Exception;

final class InvalidName extends \InvalidArgumentException implements Exception
{
    public static function create(string $name): self
    {
        return new self(\sprintf(
            'Name cannot be empty or untrimmed, but "%s" is.',
            $name
        ));
    }
}
