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

interface Variables
{
    /**
     * @param string $name
     *
     * @throws Exception\InvalidName
     *
     * @return bool
     */
    public function has(string $name): bool;

    /**
     * @param string $name
     *
     * @throws Exception\InvalidName
     *
     * @return string
     */
    public function get(string $name): string;

    /**
     * @param string $name
     * @param string $value
     *
     * @throws Exception\CouldNotSet
     * @throws Exception\InvalidName
     */
    public function set(string $name, string $value): void;

    /**
     * @param string $name
     *
     * @throws Exception\InvalidName
     */
    public function unset(string $name): void;

    /**
     * @return array<string, string>
     */
    public function toArray(): array;
}
