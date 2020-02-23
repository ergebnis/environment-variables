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

namespace Ergebnis\Environment\Test\Unit;

use Ergebnis\Environment\Exception;
use Ergebnis\Environment\Test;
use Ergebnis\Test\Util\Helper;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\Environment\Test
 *
 * @uses \Ergebnis\Environment\Exception\InvalidName
 * @uses \Ergebnis\Environment\Exception\InvalidValue
 * @uses \Ergebnis\Environment\Exception\NotBackedUp
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
     * @dataProvider \Ergebnis\Environment\Test\DataProvider\Name::invalidType()
     * @dataProvider \Ergebnis\Environment\Test\DataProvider\Name::invalidValue()
     *
     * @param int|string $name
     */
    public function testSetRejectsValuesWhenTheyHaveInvalidNames($name): void
    {
        \putenv('FOO=hmm');
        \putenv('BAR=ah');

        $environmentVariables = Test::backup(
            'FOO',
            'BAR'
        );

        $value = self::faker()->sentence;

        $this->expectException(Exception\InvalidName::class);

        $environmentVariables->set([
            $name => $value,
        ]);
    }

    /**
     * @dataProvider \Ergebnis\Environment\Test\DataProvider\Value::invalidType()
     * @dataProvider \Ergebnis\Environment\Test\DataProvider\Value::invalidValue()
     *
     * @param mixed $value
     */
    public function testSetRejectsValyuesWhenTheyHaveInvalidValues($value): void
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
        } catch (Exception\InvalidValue $exception) {
            self::assertSame('hmm', \getenv('FOO'));
            self::assertSame('ah', \getenv('BAR'));

            return;
        }

        self::fail('Failed asserting that environment variables can not be set when they have an invalid value.');
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
