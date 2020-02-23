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
use Ergebnis\Environment\FakeVariables;
use Ergebnis\Test\Util\Helper;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\Environment\FakeVariables
 *
 * @uses \Ergebnis\Environment\Exception\InvalidName
 * @uses \Ergebnis\Environment\Exception\InvalidValue
 * @uses \Ergebnis\Environment\Exception\NotSet
 * @uses \Ergebnis\Environment\TestVariables
 */
final class FakeVariablesTest extends Framework\TestCase
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

        new FakeVariables([
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

        new FakeVariables([
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
        $variables = new FakeVariables();

        $this->expectException(Exception\InvalidName::class);

        $variables->has($name);
    }

    public function testHasReturnsFalseWhenEnvironmentVariableHasNotBeenInjected(): void
    {
        $variables = new FakeVariables();

        self::assertFalse($variables->has(self::NAME));
    }

    public function testHasReturnsFalseWhenEnvironmentVariableHasBeenInjectedButValueIsFalse(): void
    {
        $variables = new FakeVariables([
            self::NAME => false,
        ]);

        self::assertFalse($variables->has(self::NAME));
    }

    public function testHasReturnsTrueWhenEnvironmentVariableHasBeenInjectedAndValueIsNotFalse(): void
    {
        $variables = new FakeVariables([
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
        $variables = new FakeVariables();

        $this->expectException(Exception\InvalidName::class);

        $variables->get($name);
    }

    public function testGetThrowsNotSetWhenEnvironmentVariableHasNotBeenInjected(): void
    {
        $variables = new FakeVariables();

        $this->expectException(Exception\NotSet::class);

        $variables->get(self::NAME);
    }

    public function testGetThrowsNotSetWhenEnvironmentVariableHasBeenInjectedButValueIsFalse(): void
    {
        $variables = new FakeVariables([
            self::NAME => false,
        ]);

        $this->expectException(Exception\NotSet::class);

        $variables->get(self::NAME);
    }

    public function testGetReturnsValueWhenEnvironmentVariableHasBeenInjectedAndValueIsNotFalse(): void
    {
        $value = self::faker()->sentence;

        $variables = new FakeVariables([
            self::NAME => $value,
        ]);

        self::assertSame($value, $variables->get(self::NAME));
    }

    /**
     * @dataProvider \Ergebnis\Environment\Test\DataProvider\Name::invalidValue()
     *
     * @param string $name
     */
    public function testSetThrowsInvalidNameWhenNameIsInvalid(string $name): void
    {
        $value = self::faker()->sentence;

        $variables = new FakeVariables();

        $this->expectException(Exception\InvalidName::class);

        $variables->set(
            $name,
            $value
        );
    }

    public function testSetSetsValue(): void
    {
        $value = self::faker()->sentence;

        $variables = new FakeVariables();

        $variables->set(
            self::NAME,
            $value
        );

        self::assertSame($value, $variables->get(self::NAME));
    }

    /**
     * @dataProvider \Ergebnis\Environment\Test\DataProvider\Name::invalidValue()
     *
     * @param string $name
     */
    public function testUnsetThrowsInvalidNameWhenNameIsInvalid(string $name): void
    {
        $variables = new FakeVariables();

        $this->expectException(Exception\InvalidName::class);

        $variables->unset($name);
    }

    public function testUnsetUnsetsVariable(): void
    {
        $value = self::faker()->sentence;

        $variables = new FakeVariables([
            self::NAME => $value,
        ]);

        $variables->unset(self::NAME);

        self::assertFalse($variables->has(self::NAME));
    }
}
