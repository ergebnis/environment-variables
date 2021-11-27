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

namespace Ergebnis\Environment;

final class TestVariables implements Variables
{
    private $systemVariables;

    private $backedUpVariables;

    /**
     * @param array<string, false|string> $backedUpVariables
     */
    private function __construct(SystemVariables $systemVariables, array $backedUpVariables)
    {
        $this->systemVariables = $systemVariables;
        $this->backedUpVariables = $backedUpVariables;
    }

    public static function backup(string ...$names): self
    {
        /** @var array<string, false> $possiblyUnsetVariables */
        $possiblyUnsetVariables = \array_combine(
            $names,
            \array_fill(
                0,
                \count($names),
                false,
            ),
        );

        /** @var array<string, string> $currentlySetVariables */
        $currentlySetVariables = \array_intersect_key(
            \getenv(),
            \array_flip($names),
        );

        $backedUpVariables = \array_merge(
            $possiblyUnsetVariables,
            $currentlySetVariables,
        );

        return new self(
            new SystemVariables(),
            $backedUpVariables,
        );
    }

    public function has(string $name): bool
    {
        if ('' === $name || \trim($name) !== $name) {
            throw Exception\InvalidName::create();
        }

        return $this->systemVariables->has($name);
    }

    public function get(string $name): string
    {
        if ('' === $name || \trim($name) !== $name) {
            throw Exception\InvalidName::create();
        }

        if (!$this->systemVariables->has($name)) {
            throw Exception\NotSet::name($name);
        }

        return $this->systemVariables->get($name);
    }

    public function set(string $name, string $value): void
    {
        if ('' === $name || \trim($name) !== $name) {
            throw Exception\InvalidName::create();
        }

        if (!\array_key_exists($name, $this->backedUpVariables)) {
            throw Exception\NotBackedUp::names($name);
        }

        try {
            $this->systemVariables->set(
                $name,
                $value,
            );
        } catch (Exception\CouldNotSet $exception) {
            throw Exception\CouldNotSet::name($name);
        }
    }

    public function unset(string $name): void
    {
        if ('' === $name || \trim($name) !== $name) {
            throw Exception\InvalidName::create();
        }

        if (!\array_key_exists($name, $this->backedUpVariables)) {
            throw Exception\NotBackedUp::names($name);
        }

        try {
            $this->systemVariables->unset($name);
        } catch (Exception\CouldNotUnset $exception) {
            throw Exception\CouldNotUnset::name($name);
        }
    }

    /**
     * @throws Exception\CouldNotSet
     * @throws Exception\CouldNotUnset
     */
    public function restore(): void
    {
        foreach ($this->backedUpVariables as $name => $value) {
            if (!\is_string($value)) {
                try {
                    $this->systemVariables->unset($name);
                } catch (Exception\CouldNotUnset $exception) {
                    throw Exception\CouldNotUnset::name($name);
                }

                continue;
            }

            try {
                $this->systemVariables->set(
                    $name,
                    $value,
                );
            } catch (Exception\CouldNotSet $exception) {
                throw Exception\CouldNotSet::name($name);
            }
        }
    }

    public function toArray(): array
    {
        return $this->systemVariables->toArray();
    }
}
