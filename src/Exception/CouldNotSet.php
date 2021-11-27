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

final class CouldNotSet extends \RuntimeException implements Exception
{
    public static function name(string $name): self
    {
        return new self(\sprintf(
            'Could not set environment variable "%s".',
            $name,
        ));
    }
}
