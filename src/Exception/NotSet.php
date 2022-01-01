<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2022 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/environment-variables
 */

namespace Ergebnis\Environment\Exception;

final class NotSet extends \InvalidArgumentException implements Exception
{
    public static function name(string $name): self
    {
        return new self(\sprintf(
            'The environment variable "%s" is not set.',
            $name,
        ));
    }
}
