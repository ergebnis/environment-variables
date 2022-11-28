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

namespace Ergebnis\Environment;

final class ReadOnlyVariables implements Variables
{
    /**
     * @param array<string, string> $values
     */
    private function __construct(private array $values)
    {
    }

    public static function empty(): self
    {
        return new self([]);
    }

    /**
     * @param array<string, false|string> $values
     *
     * @throws Exception\InvalidName
     * @throws Exception\InvalidValue
     */
    public static function fromArray(array $values): self
    {
        $invalidNames = \array_filter(\array_keys($values), static function ($name): bool {
            return !\is_string($name)
                || '' === $name
                || \trim($name) !== $name;
        });

        if ([] !== $invalidNames) {
            throw Exception\InvalidName::create();
        }

        $invalidValues = \array_filter($values, static function ($value): bool {
            return !\is_string($value)
                && false !== $value;
        });

        if ([] !== $invalidValues) {
            throw Exception\InvalidValue::create();
        }

        return new self(\array_filter($values, static function ($value): bool {
            return \is_string($value);
        }));
    }

    public function has(string $name): bool
    {
        if (
            '' === $name
            || \trim($name) !== $name
        ) {
            throw Exception\InvalidName::create();
        }

        return \array_key_exists($name, $this->values);
    }

    public function get(string $name): string
    {
        if (
            '' === $name
            || \trim($name) !== $name
        ) {
            throw Exception\InvalidName::create();
        }

        if (!\array_key_exists($name, $this->values)) {
            throw Exception\NotSet::name($name);
        }

        return $this->values[$name];
    }

    public function set(
        string $name,
        string $value,
    ): void {
        throw Exception\ShouldNotBeUsed::create(__METHOD__);
    }

    public function unset(string $name): void
    {
        throw Exception\ShouldNotBeUsed::create(__METHOD__);
    }

    public function toArray(): array
    {
        return $this->values;
    }
}
