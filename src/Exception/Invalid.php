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

final class Invalid extends \InvalidArgumentException implements Exception
{
    public static function names(): self
    {
        return new self('Keys need to be string values and cannot be empty or untrimmed.');
    }

    public static function values(): self
    {
        return new self('Values need to be either string values or false.');
    }
}
