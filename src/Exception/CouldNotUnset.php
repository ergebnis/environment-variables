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

namespace Ergebnis\Environment\Exception;

final class CouldNotUnset extends \RuntimeException implements Exception
{
    public static function name(string $name): self
    {
        return new self(\sprintf(
            'Could not unset environment variable "%s".',
            $name,
        ));
    }
}
