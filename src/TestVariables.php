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

final class TestVariables
{
    private $values;

    /**
     * @param array<string, false|string> $values
     */
    private function __construct(array $values)
    {
        $this->values = $values;
    }

    public static function backup(string ...$names): self
    {
        /** @var array<string, false|string> $values */
        $values = \array_intersect_key(
            \getenv(),
            \array_flip($names)
        );

        return new self($values);
    }

    /**
     * @param array<string, false|string> $values
     *
     * @throws Exception\InvalidName
     * @throws Exception\InvalidValue
     * @throws Exception\NotBackedUp
     */
    public function set(array $values): void
    {
        $invalidNames = \array_filter(\array_keys($values), static function ($name): bool {
            return !\is_string($name) || '' === $name || \trim($name) !== $name;
        });

        if ([] !== $invalidNames) {
            throw Exception\InvalidName::create();
        }

        $invalidValues = \array_filter($values, static function ($value): bool {
            return !\is_string($value) && false !== $value;
        });

        if ([] !== $invalidValues) {
            throw Exception\InvalidValue::create();
        }

        /** @var string[] $notBackedUp */
        $notBackedUp = \array_diff(
            \array_keys($values),
            \array_keys($this->values)
        );

        if ([] !== $notBackedUp) {
            throw Exception\NotBackedUp::names(...$notBackedUp);
        }

        foreach ($values as $name => $value) {
            if (false === $value) {
                \putenv($name);

                continue;
            }

            \putenv(\sprintf(
                '%s=%s',
                $name,
                $value
            ));
        }
    }

    public function restore(): void
    {
        $this->set($this->values);
    }
}
