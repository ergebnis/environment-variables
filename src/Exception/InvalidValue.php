<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2023 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/environment-variables
 */

namespace Ergebnis\Environment\Exception;

final class InvalidValue extends \InvalidArgumentException implements Exception
{
    public static function create(): self
    {
        return new self('Value needs to be a string or false.');
    }
}
