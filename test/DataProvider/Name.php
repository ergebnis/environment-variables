<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2023 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/environment-variables
 */

namespace Ergebnis\Environment\Test\DataProvider;

use Ergebnis\DataProvider;

final class Name extends DataProvider\AbstractProvider
{
    /**
     * @return \Generator<string, array<int>>
     */
    public static function invalidType(): \Generator
    {
        $faker = self::faker();

        return self::provideDataForValues([
            'int-greater-than-one' => $faker->numberBetween(2),
            'int-less-than-minus-one' => -1 * $faker->numberBetween(2),
            'int-minus-one' => -1,
            'int-one' => 1,
            'int-zero' => 0,
        ]);
    }

    /**
     * @return \Generator<string, array<string>>
     */
    public static function invalidValue(): \Generator
    {
        return self::provideDataForValues([
            'string-blank' => ' ',
            'string-empty' => '',
            'string-untrimmed' => \sprintf(
                ' %s ',
                self::faker()->sentence(),
            ),
        ]);
    }
}
