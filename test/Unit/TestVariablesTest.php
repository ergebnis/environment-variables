<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2022 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/environment-variables
 */

namespace Ergebnis\Environment\Test\Unit;

use Ergebnis\Environment\Exception;
use Ergebnis\Environment\Test;
use Ergebnis\Environment\TestVariables;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\Environment\TestVariables
 *
 * @uses \Ergebnis\Environment\Exception\InvalidName
 * @uses \Ergebnis\Environment\Exception\InvalidValue
 * @uses \Ergebnis\Environment\Exception\NotBackedUp
 * @uses \Ergebnis\Environment\Exception\NotSet
 * @uses \Ergebnis\Environment\SystemVariables
 */
final class TestVariablesTest extends Framework\TestCase
{
    use Test\Util\Helper;
    private const NAME = 'QUX';

    /**
     * @var array<string, false|string>
     */
    private static array $env = [];

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
                $value,
            ));
        }
    }

    /**
     * @dataProvider \Ergebnis\Environment\Test\DataProvider\Name::invalidValue()
     */
    public function testHasThrowsInvalidNameWhenNameIsInvalid(string $name): void
    {
        $variables = TestVariables::backup();

        $this->expectException(Exception\InvalidName::class);

        $variables->has($name);
    }

    public function testHasReturnsFalseWhenEnvironmentVariableIsNotSet(): void
    {
        $variables = TestVariables::backup();

        self::assertFalse($variables->has(self::NAME));
    }

    public function testHasReturnsTrueWhenEnvironmentVariableIsSet(): void
    {
        \putenv(\sprintf(
            '%s=%s',
            self::NAME,
            self::faker()->sentence,
        ));

        $variables = TestVariables::backup();

        self::assertTrue($variables->has(self::NAME));
    }

    /**
     * @dataProvider \Ergebnis\Environment\Test\DataProvider\Name::invalidValue()
     */
    public function testGetThrowsInvalidNameWhenNameIsInvalid(string $name): void
    {
        $variables = TestVariables::backup();

        $this->expectException(Exception\InvalidName::class);

        $variables->get($name);
    }

    public function testGetThrowsNotSetFalseWhenEnvironmentVariableIsNotSet(): void
    {
        $variables = TestVariables::backup();

        $this->expectException(Exception\NotSet::class);

        $variables->get(self::NAME);
    }

    public function testGetReturnsValueWhenEnvironmentVariableIsSet(): void
    {
        $value = self::faker()->sentence();

        \putenv(\sprintf(
            '%s=%s',
            self::NAME,
            $value,
        ));

        $variables = TestVariables::backup();

        self::assertSame($value, $variables->get(self::NAME));
    }

    /**
     * @dataProvider \Ergebnis\Environment\Test\DataProvider\Name::invalidValue()
     */
    public function testSetThrowsInvalidNameWhenNameIsInvalid(string $name): void
    {
        $value = self::faker()->sentence();

        $variables = TestVariables::backup();

        $this->expectException(Exception\InvalidName::class);

        $variables->set(
            $name,
            $value,
        );
    }

    public function testSetThrowsNotBackedUpWhenVariableHasNotBeenBackedUp(): void
    {
        $value = self::faker()->sentence();

        $variables = TestVariables::backup();

        $this->expectException(Exception\NotBackedUp::class);

        $variables->set(
            self::NAME,
            $value,
        );
    }

    public function testSetSetsValueWhenItHasBeenBackedUp(): void
    {
        $value = self::faker()->sentence();

        $variables = TestVariables::backup(self::NAME);

        $variables->set(
            self::NAME,
            $value,
        );

        self::assertSame($value, \getenv(self::NAME));
    }

    /**
     * @dataProvider \Ergebnis\Environment\Test\DataProvider\Name::invalidValue()
     */
    public function testUnsetThrowsInvalidNameWhenNameIsInvalid(string $name): void
    {
        $variables = TestVariables::backup();

        $this->expectException(Exception\InvalidName::class);

        $variables->unset($name);
    }

    public function testUnsetThrowsNotBackedUpWhenVariableHasNotBeenBackedUp(): void
    {
        $variables = TestVariables::backup();

        $this->expectException(Exception\NotBackedUp::class);

        $variables->unset(self::NAME);
    }

    public function testUnsetUnsetsVariableWhenVariableHasBeenBackedUp(): void
    {
        $value = self::faker()->sentence();

        \putenv(\sprintf(
            '%s=%s',
            self::NAME,
            $value,
        ));

        $variables = TestVariables::backup(self::NAME);

        $variables->unset(self::NAME);

        self::assertFalse(\getenv(self::NAME));
    }

    public function testEnvironmentVariablesCanBeBackedUpAndRestored(): void
    {
        \putenv('FOO=hmm');
        \putenv('BAR=ah');

        $environmentVariables = TestVariables::backup(
            'FOO',
            'BAR',
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

        $environmentVariables = TestVariables::backup(
            'FOO',
            'BAR',
        );

        \putenv('FOO=ok');
        \putenv('BAR=uh');
        \putenv('BAZ=oops');

        $environmentVariables->restore();

        self::assertSame('hmm', \getenv('FOO'));
        self::assertSame('ah', \getenv('BAR'));
        self::assertSame('oops', \getenv('BAZ'));
    }

    public function testToArrayReturnsCurrentValues(): void
    {
        $variables = TestVariables::backup();

        self::assertSame(\getenv(), $variables->toArray());
    }

    public function testToArrayReturnsValuesWhenValueHasBeenSet(): void
    {
        $value = self::faker()->sentence();

        \putenv(\sprintf(
            '%s=%s',
            self::NAME,
            $value,
        ));

        $variables = TestVariables::backup();

        self::assertSame(\getenv(), $variables->toArray());
    }

    public function testToArrayReturnsValuesWhenValueHasBeenUnset(): void
    {
        \putenv(self::NAME);

        $variables = TestVariables::backup();

        self::assertSame(\getenv(), $variables->toArray());
    }
}
