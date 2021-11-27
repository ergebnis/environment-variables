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

namespace Ergebnis\Environment\Test\DataProvider;

use Ergebnis\DataProvider;

final class Value extends DataProvider\AbstractProvider
{
    /**
     * @return \Generator<string, array<int, null|array|float|int|resource|\stdClass|true>>
     */
    public static function invalidType(): \Generator
    {
        $faker = self::faker();

        /** @var resource $resource */
        $resource = \fopen(__FILE__, 'rb');

        return self::provideDataForValues([
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
        ]);
    }

    /**
     * @return \Generator<string, array<null|int|object|true>>
     */
    public static function invalidValue(): \Generator
    {
        return self::provideDataForValues([
            'bool-true' => true,
        ]);
    }
}
