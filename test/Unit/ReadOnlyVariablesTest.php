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
use Ergebnis\Environment\ReadOnlyVariables;
use Ergebnis\Test\Util\Helper;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\Environment\ReadOnlyVariables
 *
 * @uses \Ergebnis\Environment\Exception\InvalidName
 * @uses \Ergebnis\Environment\Exception\InvalidValue
 * @uses \Ergebnis\Environment\Exception\ShouldNotBeUsed
 * @uses \Ergebnis\Environment\Test
 */
final class ReadOnlyVariablesTest extends Framework\TestCase
{
    use Helper;

    private const NAME = 'FOO';

    /**
     * @dataProvider \Ergebnis\Environment\Test\DataProvider\Name::invalidValue()
     * @dataProvider \Ergebnis\Environment\Test\DataProvider\Name::invalidType()
     *
     * @param mixed $name
     */
    public function testConstructorRejectsValuesWhenTheyHaveInvalidNames($name): void
    {
        $value = self::faker()->sentence;

        $this->expectException(Exception\InvalidName::class);

        new ReadOnlyVariables([
            $name => $value,
        ]);
    }

    /**
     * @dataProvider \Ergebnis\Environment\Test\DataProvider\Value::invalidType()
     * @dataProvider \Ergebnis\Environment\Test\DataProvider\Value::invalidValue()
     *
     * @param mixed $value
     */
    public function testConstructorRejectsValuesWhenTheyHaveInvalidValues($value): void
    {
        $this->expectException(Exception\InvalidValue::class);

        new ReadOnlyVariables([
            self::NAME => $value,
        ]);
    }

    /**
     * @dataProvider \Ergebnis\Environment\Test\DataProvider\Name::invalidValue()
     *
     * @param string $name
     */
    public function testHasThrowsInvalidNameWhenNameIsInvalid(string $name): void
    {
        $variables = new ReadOnlyVariables();

        $this->expectException(Exception\InvalidName::class);

        $variables->has($name);
    }

    public function testHasReturnsFalseWhenEnvironmentVariableHasNotBeenInjected(): void
    {
        $variables = new ReadOnlyVariables();

        self::assertFalse($variables->has(self::NAME));
    }

    public function testHasReturnsFalseWhenEnvironmentVariableHasBeenInjectedButValueIsFalse(): void
    {
        $variables = new ReadOnlyVariables([
            self::NAME => false,
        ]);

        self::assertFalse($variables->has(self::NAME));
    }

    public function testHasReturnsTrueWhenEnvironmentVariableHasBeenInjectedAndValueIsNotFalse(): void
    {
        $variables = new ReadOnlyVariables([
            self::NAME => self::faker()->sentence,
        ]);

        self::assertTrue($variables->has(self::NAME));
    }

    /**
     * @dataProvider \Ergebnis\Environment\Test\DataProvider\Name::invalidValue()
     *
     * @param string $name
     */
    public function testGetThrowsInvalidNameWhenNameIsInvalid(string $name): void
    {
        $variables = new ReadOnlyVariables();

        $this->expectException(Exception\InvalidName::class);

        $variables->get($name);
    }

    public function testGetReturnsFalseWhenEnvironmentVariableHasNotBeenInjected(): void
    {
        $variables = new ReadOnlyVariables();

        self::assertFalse($variables->get(self::NAME));
    }

    public function testGetReturnsFalseWhenEnvironmentVariableHasBeenInjectedButValueIsFalse(): void
    {
        $variables = new ReadOnlyVariables([
            self::NAME => false,
        ]);

        self::assertFalse($variables->get(self::NAME));
    }

    public function testGetReturnsValueWhenEnvironmentVariableHasBeenInjectedAndValueIsNotFalse(): void
    {
        $value = self::faker()->sentence;

        $variables = new ReadOnlyVariables([
            self::NAME => $value,
        ]);

        self::assertSame($value, $variables->get(self::NAME));
    }

    public function testSetThrowsShouldNotBeUsed(): void
    {
        $value = self::faker()->sentence;

        $variables = new ReadOnlyVariables();

        $this->expectException(Exception\ShouldNotBeUsed::class);

        $variables->set(
            self::NAME,
            $value
        );
    }

    public function testUnsetThrowsShouldNotBeUsed(): void
    {
        $value = self::faker()->sentence;

        $variables = new ReadOnlyVariables([
            self::NAME => $value,
        ]);

        $this->expectException(Exception\ShouldNotBeUsed::class);

        $variables->unset(self::NAME);
    }
}
