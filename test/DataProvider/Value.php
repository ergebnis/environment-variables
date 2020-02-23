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

namespace Ergebnis\Environment\Test\DataProvider;

use Ergebnis\Test\Util\Helper;

final class Value
{
    use Helper;

    /**
     * @return \Generator<string, array<int, null|array|float|int|resource|\stdClass|true>>
     */
    public static function invalidType(): \Generator
    {
        $faker = self::faker();

        /** @var resource $resource */
        $resource = \fopen(__FILE__, 'rb');

        $values = [
            'array' => [
                $faker->word,
                $faker->word,
                $faker->word,
            ],
            'float' => $faker->randomFloat(),
            'int' => $faker->numberBetween(2),
            'null' => null,
            'object' => new \stdClass(),
            'resource' => $resource,
        ];

        foreach ($values as $key => $value) {
            yield $key => [
                $value,
            ];
        }
    }

    /**
     * @return \Generator<string, array<null|int|object|true>>
     */
    public static function invalidValue(): \Generator
    {
        $values = [
            'bool-true' => true,
        ];

        foreach ($values as $key => $value) {
            yield $key => [
                $value,
            ];
        }
    }
}
