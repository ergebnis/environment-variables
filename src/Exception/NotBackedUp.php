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

final class NotBackedUp extends \InvalidArgumentException implements Exception
{
    public static function names(string ...$names): self
    {
        return new self(\sprintf(
            'The environment variables "%s" have not been backed up. Are you sure you want to modify them?',
            \implode('", "', $names),
        ));
    }
}
