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

namespace Ergebnis\Environment\Variables\Test\Unit;

use Ergebnis\Environment\Variables\Exception;
use Ergebnis\Environment\Variables\Test;
use Ergebnis\Test\Util\Helper;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\Environment\Variables\Test
 *
 * @uses \Ergebnis\Environment\Variables\Exception\Invalid
 * @uses \Ergebnis\Environment\Variables\Exception\NotBackedUp
 */
final class TestTest extends Framework\TestCase
{
    use Helper;

    /**
     * @var array<string, false|string>
     */
    private static $env = [];

    protected function setUp(): void
    {
        $names = [
            'FOO',
            'BAR',
            'BAZ',
            'QUX',
        ];

        foreach ($names as $name) {
            self::$env[$name] = \getenv($name);

            \putenv($name);
        }
    }

    protected function tearDown(): void
    {
        foreach (self::$env as $name => $value) {
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

    public function testEnvironmentVariablesCanBeBackedUpAndRestored(): void
    {
        \putenv('FOO=hmm');
        \putenv('BAR=ah');

        $environmentVariables = Test::backup(
            'FOO',
            'BAR'
        );

        \putenv('FOO=ok');
        \putenv('BAR=uh');

        $environmentVariables->restore();

        self::assertSame('hmm', \getenv('FOO'));
        self::assertSame('ah', \getenv('BAR'));
    }

    public function testEnvironmentVariablesThatHaveNotBeenBackedUpWillNotBeRestored(): void
    {
        \putenv('FOO=hmm');
        \putenv('BAR=ah');
        \putenv('BAZ=oh');

        $environmentVariables = Test::backup(
            'FOO',
            'BAR'
        );

        \putenv('FOO=ok');
        \putenv('BAR=uh');
        \putenv('BAZ=oops');

        $environmentVariables->restore();

        self::assertSame('hmm', \getenv('FOO'));
        self::assertSame('ah', \getenv('BAR'));
        self::assertSame('oops', \getenv('BAZ'));
    }

    /**
     * @dataProvider provideInvalidKey
     * @dataProvider \Ergebnis\Environment\Variables\Test\DataProvider\Name::invalid()
     *
     * @param int|string $key
     */
    public function testEnvironmentVariablesCanNotBeSetWhenTheyHaveInvalidKeys($key): void
    {
        \putenv('FOO=hmm');
        \putenv('BAR=ah');

        $environmentVariables = Test::backup(
            'FOO',
            'BAR'
        );

        $value = self::faker()->sentence;

        $this->expectException(Exception\Invalid::class);
        $this->expectExceptionMessage('Keys need to be string values and cannot be empty or untrimmed.');

        $environmentVariables->set([
            $key => $value,
        ]);
    }

    public function provideInvalidKey(): \Generator
    {
        $faker = self::faker();

        $values = [
            'int-greater-than-one' => $faker->numberBetween(2),
            'int-less-than-minus-one' => -1 * $faker->numberBetween(2),
            'int-minus-one' => -1,
            'int-one' => 1,
            'int-zero' => 0,
        ];

        foreach ($values as $key => $value) {
            yield $key => [
                $value,
            ];
        }
    }

    /**
     * @dataProvider provideInvalidValue
     *
     * @param null|array|int|\stdClass|true $value
     */
    public function testEnvironmentVariablesCanNotBeSetWhenTheyHaveInvalidValues($value): void
    {
        \putenv('FOO=hmm');
        \putenv('BAR=ah');

        $environmentVariables = Test::backup(
            'FOO',
            'BAR'
        );

        try {
            $environmentVariables->set([
                'BAR' => $value,
            ]);
        } catch (Exception\Invalid $exception) {
            self::assertSame('Values need to be either string values or false.', $exception->getMessage());
            self::assertSame('hmm', \getenv('FOO'));
            self::assertSame('ah', \getenv('BAR'));

            return;
        }

        self::fail('Failed asserting that environment variables can not be set when they have an invalid value.');
    }

    public function provideInvalidValue(): \Generator
    {
        $faker = self::faker();

        $values = [
            'int-greater-than-one' => $faker->numberBetween(2),
            'int-less-than-minus-one' => -1 * $faker->numberBetween(2),
            'int-minus-one' => -1,
            'int-one' => 1,
            'int-zero' => 0,
            'null' => null,
            'bool-true' => true,
        ];

        foreach ($values as $key => $value) {
            yield $key => [
                $value,
            ];
        }
    }

    public function testEnvironmentVariablesCanNotBeSetWhenTheyHaveNotBeenBackedUp(): void
    {
        \putenv('FOO=hmm');
        \putenv('BAR=ah');

        $environmentVariables = Test::backup(
            'FOO',
            'BAR'
        );

        try {
            $environmentVariables->set([
                'FOO' => 'ok',
                'BAR' => false,
                'BAZ' => 'oh no',
                'QUX' => 'haha',
            ]);
        } catch (Exception\NotBackedUp $exception) {
            self::assertSame('The environment variables "BAZ", "QUX" have not been backed up. Are you sure you want to modify them?', $exception->getMessage());
            self::assertSame('hmm', \getenv('FOO'));
            self::assertSame('ah', \getenv('BAR'));
            self::assertFalse(\getenv('BAZ'));

            return;
        }

        self::fail('Failed asserting that environment variables can not be modified when they have not been backed before.');
    }

    public function testEnvironmentVariablesCanBeBackedUpAndSet(): void
    {
        \putenv('FOO=hmm');
        \putenv('BAR=ah');
        \putenv('BAZ=oho');

        $environmentVariables = Test::backup(
            'FOO',
            'BAR',
            'BAZ'
        );

        $environmentVariables->set([
            'FOO' => 'ok',
            'BAR' => false,
            'BAZ' => ' well, then ',
        ]);

        self::assertSame('ok', \getenv('FOO'));
        self::assertFalse(\getenv('BAR'));
        self::assertSame(' well, then ', \getenv('BAZ'));
    }
}
