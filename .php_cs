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

use Ergebnis\License;
use Ergebnis\PhpCsFixer\Config;

$license = License\Type\MIT::markdown(
    __DIR__ . '/LICENSE.md',
    License\Range::since(
        License\Year::fromString('2020'),
        new \DateTimeZone('UTC')
    ),
    License\Holder::fromString('Andreas Möller'),
    License\Url::fromString('https://github.com/ergebnis/environment-variables')
);

$license->save();

$config = Config\Factory::fromRuleSet(new Config\RuleSet\Php71($license->header()), [
    'php_unit_internal_class' => false,
    'php_unit_test_class_requires_covers' => false,
]);

$config->getFinder()
    ->ignoreDotFiles(false)
    ->in(__DIR__)
    ->exclude([
        '.build',
        '.dependabot',
        '.github',
    ])
    ->name('.php_cs');

$config->setCacheFile(__DIR__ . '/.build/php-cs-fixer/.php_cs.cache');

return $config;
