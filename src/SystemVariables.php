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

final class SystemVariables implements Variables
{
    public function has(string $name): bool
    {
        if ('' === $name || \trim($name) !== $name) {
            throw Exception\InvalidName::create();
        }

        return false !== \getenv($name);
    }

    public function get(string $name): string
    {
        if ('' === $name || \trim($name) !== $name) {
            throw Exception\InvalidName::create();
        }

        $value = \getenv($name);

        if (!\is_string($value)) {
            throw Exception\NotSet::name($name);
        }

        return $value;
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
            throw Exception\CouldNotSet::name($name);
        }
    }

    public function unset(string $name): void
    {
        if ('' === $name || \trim($name) !== $name) {
            throw Exception\InvalidName::create();
        }

        $hasBeenUnset = \putenv($name);

        if (false === $hasBeenUnset) {
            throw Exception\CouldNotUnset::name($name);
        }
    }
}
