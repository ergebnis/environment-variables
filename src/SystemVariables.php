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

namespace Ergebnis\Environment;

use Ergebnis\Environment\Exception\CouldNotSet;

final class SystemVariables implements Variables
{
    public function has(string $name): bool
    {
        if ('' === $name || \trim($name) !== $name) {
            throw Exception\InvalidName::create();
        }

        return false !== \getenv($name);
    }

    public function get(string $name)
    {
        if ('' === $name || \trim($name) !== $name) {
            throw Exception\InvalidName::create();
        }

        return \getenv($name);
    }

    public function set(string $name, string $value): void
    {
        if ('' === $name || \trim($name) !== $name) {
            throw Exception\InvalidName::create();
        }

        $hasBeenSet = \putenv(\sprintf(
            '%s=%s',
            $name,
            $value
        ));

        if (false === $hasBeenSet) {
            throw CouldNotSet::name($name);
        }
    }

    public function unset(string $name): void
    {
        if ('' === $name || \trim($name) !== $name) {
            throw Exception\InvalidName::create();
        }

        \putenv($name);
    }
}
