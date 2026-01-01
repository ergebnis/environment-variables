<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2026 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/environment-variables
 */

namespace Ergebnis\Environment;

interface Variables
{
    /**
     * @throws Exception\InvalidName
     */
    public function has(string $name): bool;

    /**
     * @throws Exception\InvalidName
     */
    public function get(string $name): string;

    /**
     * @throws Exception\CouldNotSet
     * @throws Exception\InvalidName
     */
    public function set(string $name, string $value): void;

    /**
     * @throws Exception\InvalidName
     */
    public function unset(string $name): void;

    /**
     * @return array<string, string>
     */
    public function toArray(): array;
}
