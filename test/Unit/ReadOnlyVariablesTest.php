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

namespace Ergebnis\Environment\Test\Unit;

use Ergebnis\Environment\Exception;
use Ergebnis\Environment\ReadOnlyVariables;
use Ergebnis\Environment\Test;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\Environment\ReadOnlyVariables
 *
 * @uses \Ergebnis\Environment\Exception\InvalidName
 * @uses \Ergebnis\Environment\Exception\InvalidValue
 * @uses \Ergebnis\Environment\Exception\NotSet
 * @uses \Ergebnis\Environment\Exception\ShouldNotBeUsed
 * @uses \Ergebnis\Environment\TestVariables
 */
final class ReadOnlyVariablesTest extends Framework\TestCase
{
    use Test\Util\Helper;
    private const NAME = 'FOO';

    public function testEmptyReturnsEmptyVariables(): void
    {
        $variables = ReadOnlyVariables::empty();

        self::assertSame([], $variables->toArray());
    }

    /**
     * @dataProvider \Ergebnis\Environment\Test\DataProvider\Name::invalidType()
     * @dataProvider \Ergebnis\Environment\Test\DataProvider\Name::invalidValue()
     *
     * @param mixed $name
     */
    public function testFromArrayRejectsValuesWhenTheyHaveInvalidNames($name): void
    {
        $value = self::faker()->sentence;

        $this->expectException(Exception\InvalidName::class);

        ReadOnlyVariables::fromArray([
            $name => $value,
        ]);
    }

    /**
     * @dataProvider \Ergebnis\Environment\Test\DataProvider\Value::invalidType()
     * @dataProvider \Ergebnis\Environment\Test\DataProvider\Value::invalidValue()
     *
     * @param mixed $value
     */
    public function testFromArrayRejectsValuesWhenTheyHaveInvalidValues($value): void
    {
        $this->expectException(Exception\InvalidValue::class);

        ReadOnlyVariables::fromArray([
            self::NAME => $value,
        ]);
    }

    /**
     * @dataProvider \Ergebnis\Environment\Test\DataProvider\Name::invalidValue()
     */
    public function testHasThrowsInvalidNameWhenNameIsInvalid(string $name): void
    {
        $variables = ReadOnlyVariables::empty();

        $this->expectException(Exception\InvalidName::class);

        $variables->has($name);
    }

    public function testHasReturnsFalseWhenEnvironmentVariableHasNotBeenInjected(): void
    {
        $variables = ReadOnlyVariables::empty();

        self::assertFalse($variables->has(self::NAME));
    }

    public function testHasReturnsFalseWhenEnvironmentVariableHasBeenInjectedButValueIsFalse(): void
    {
        $variables = ReadOnlyVariables::fromArray([
            self::NAME => false,
        ]);

        self::assertFalse($variables->has(self::NAME));
    }

    public function testHasReturnsTrueWhenEnvironmentVariableHasBeenInjectedAndValueIsNotFalse(): void
    {
        $variables = ReadOnlyVariables::fromArray([
            self::NAME => self::faker()->sentence,
        ]);

        self::assertTrue($variables->has(self::NAME));
    }

    /**
     * @dataProvider \Ergebnis\Environment\Test\DataProvider\Name::invalidValue()
     */
    public function testGetThrowsInvalidNameWhenNameIsInvalid(string $name): void
    {
        $variables = ReadOnlyVariables::empty();

        $this->expectException(Exception\InvalidName::class);

        $variables->get($name);
    }

    public function testGetThrowsNotSetWhenEnvironmentVariableHasNotBeenInjected(): void
    {
        $variables = ReadOnlyVariables::empty();

        $this->expectException(Exception\NotSet::class);

        $variables->get(self::NAME);
    }

    public function testGetThrowsNotSetWhenEnvironmentVariableHasBeenInjectedButValueIsFalse(): void
    {
        $variables = ReadOnlyVariables::fromArray([
            self::NAME => false,
        ]);

        $this->expectException(Exception\NotSet::class);

        $variables->get(self::NAME);
    }

    public function testGetReturnsValueWhenEnvironmentVariableHasBeenInjectedAndValueIsNotFalse(): void
    {
        $value = self::faker()->sentence;

        $variables = ReadOnlyVariables::fromArray([
            self::NAME => $value,
        ]);

        self::assertSame($value, $variables->get(self::NAME));
    }

    public function testSetThrowsShouldNotBeUsed(): void
    {
        $value = self::faker()->sentence;

        $variables = ReadOnlyVariables::empty();

        $this->expectException(Exception\ShouldNotBeUsed::class);

        $variables->set(
            self::NAME,
            $value,
        );
    }

    public function testUnsetThrowsShouldNotBeUsed(): void
    {
        $value = self::faker()->sentence;

        $variables = ReadOnlyVariables::fromArray([
            self::NAME => $value,
        ]);

        $this->expectException(Exception\ShouldNotBeUsed::class);

        $variables->unset(self::NAME);
    }

    public function testToArrayReturnsInjectedValues(): void
    {
        $values = [
            'FOO' => '9000',
            'BAR' => 'ok',
            'BAZ' => 'aha',
        ];

        $variables = ReadOnlyVariables::fromArray($values);

        self::assertSame($values, $variables->toArray());
    }
}
