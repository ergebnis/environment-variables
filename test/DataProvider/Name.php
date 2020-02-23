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

final class Name
{
    use Helper;

    /**
     * @return \Generator<string, array<string>>
     */
    public static function invalid(): \Generator
    {
        $values = [
            'string-blank' => ' ',
            'string-empty' => '',
            'string-untrimmed' => \sprintf(
                ' %s ',
                self::faker()->sentence
            ),
        ];

        foreach ($values as $key => $value) {
            yield $key => [
                $value,
            ];
        }
    }
}
