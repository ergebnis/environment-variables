<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2025 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/environment-variables
 */

namespace Ergebnis\Environment\Test\Unit;

use Ergebnis\Environment\Exception;
use Ergebnis\Environment\FakeVariables;
use Ergebnis\Environment\Test;
use Ergebnis\Environment\TestVariables;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(FakeVariables::class)]
#[Framework\Attributes\UsesClass(Exception\InvalidName::class)]
#[Framework\Attributes\UsesClass(Exception\InvalidValue::class)]
#[Framework\Attributes\UsesClass(Exception\NotSet::class)]
#[Framework\Attributes\UsesClass(TestVariables::class)]
final class FakeVariablesTest extends Framework\TestCase
{
    use Test\Util\Helper;
    private const NAME = 'FOO';

    public function testEmptyReturnsEmptyVariables(): void
    {
        $variables = FakeVariables::empty();

        self::assertSame([], $variables->toArray());
    }

    /**
     * @param mixed $name
     */
    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\Name::class, 'invalidType')]
    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\Name::class, 'invalidValue')]
    public function testFromArrayRejectsValuesWhenTheyHaveInvalidNames($name): void
    {
        $value = self::faker()->sentence();

        $this->expectException(Exception\InvalidName::class);

        FakeVariables::fromArray([
            $name => $value,
        ]);
    }

    /**
     * @param mixed $value
     */
    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\Value::class, 'invalidType')]
    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\Value::class, 'invalidValue')]
    public function testFromArrayRejectsValuesWhenTheyHaveInvalidValues($value): void
    {
        $this->expectException(Exception\InvalidValue::class);

        FakeVariables::fromArray([
            self::NAME => $value,
        ]);
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\Name::class, 'invalidValue')]
    public function testHasThrowsInvalidNameWhenNameIsInvalid(string $name): void
    {
        $variables = FakeVariables::empty();

        $this->expectException(Exception\InvalidName::class);

        $variables->has($name);
    }

    public function testHasReturnsFalseWhenEnvironmentVariableHasNotBeenInjected(): void
    {
        $variables = FakeVariables::empty();

        self::assertFalse($variables->has(self::NAME));
    }

    public function testHasReturnsFalseWhenEnvironmentVariableHasBeenInjectedButValueIsFalse(): void
    {
        $variables = FakeVariables::fromArray([
            self::NAME => false,
        ]);

        self::assertFalse($variables->has(self::NAME));
    }

    public function testHasReturnsTrueWhenEnvironmentVariableHasBeenInjectedAndValueIsNotFalse(): void
    {
        $variables = FakeVariables::fromArray([
            self::NAME => self::faker()->sentence(),
        ]);

        self::assertTrue($variables->has(self::NAME));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\Name::class, 'invalidValue')]
    public function testGetThrowsInvalidNameWhenNameIsInvalid(string $name): void
    {
        $variables = FakeVariables::empty();

        $this->expectException(Exception\InvalidName::class);

        $variables->get($name);
    }

    public function testGetThrowsNotSetWhenEnvironmentVariableHasNotBeenInjected(): void
    {
        $variables = FakeVariables::empty();

        $this->expectException(Exception\NotSet::class);

        $variables->get(self::NAME);
    }

    public function testGetThrowsNotSetWhenEnvironmentVariableHasBeenInjectedButValueIsFalse(): void
    {
        $variables = FakeVariables::fromArray([
            self::NAME => false,
        ]);

        $this->expectException(Exception\NotSet::class);

        $variables->get(self::NAME);
    }

    public function testGetReturnsValueWhenEnvironmentVariableHasBeenInjectedAndValueIsNotFalse(): void
    {
        $value = self::faker()->sentence();

        $variables = FakeVariables::fromArray([
            self::NAME => $value,
        ]);

        self::assertSame($value, $variables->get(self::NAME));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\Name::class, 'invalidValue')]
    public function testSetThrowsInvalidNameWhenNameIsInvalid(string $name): void
    {
        $value = self::faker()->sentence();

        $variables = FakeVariables::empty();

        $this->expectException(Exception\InvalidName::class);

        $variables->set(
            $name,
            $value,
        );
    }

    public function testSetSetsValue(): void
    {
        $value = self::faker()->sentence();

        $variables = FakeVariables::empty();

        $variables->set(
            self::NAME,
            $value,
        );

        self::assertSame($value, $variables->get(self::NAME));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\Name::class, 'invalidValue')]
    public function testUnsetThrowsInvalidNameWhenNameIsInvalid(string $name): void
    {
        $variables = FakeVariables::empty();

        $this->expectException(Exception\InvalidName::class);

        $variables->unset($name);
    }

    public function testUnsetUnsetsVariable(): void
    {
        $value = self::faker()->sentence();

        $variables = FakeVariables::fromArray([
            self::NAME => $value,
        ]);

        $variables->unset(self::NAME);

        self::assertFalse($variables->has(self::NAME));
    }

    public function testToArrayReturnsInjectedValues(): void
    {
        $values = [
            'FOO' => '9000',
            'BAR' => 'ok',
            'BAZ' => 'aha',
        ];

        $variables = FakeVariables::fromArray($values);

        self::assertSame($values, $variables->toArray());
    }

    public function testToArrayReturnsValuesWhenValueHasBeenSet(): void
    {
        $values = [
            'FOO' => '9000',
            'BAR' => 'ok',
            'BAZ' => 'aha',
        ];

        $variables = FakeVariables::fromArray($values);

        $variables->set(
            'FOO',
            '9001',
        );

        $expected = [
            'FOO' => '9001',
            'BAR' => 'ok',
            'BAZ' => 'aha',
        ];

        self::assertSame($expected, $variables->toArray());
    }

    public function testToArrayReturnsValuesWhenValueHasBeenUnset(): void
    {
        $values = [
            'FOO' => '9000',
            'BAR' => 'ok',
            'BAZ' => 'aha',
        ];

        $variables = FakeVariables::fromArray($values);

        $variables->unset('FOO');

        $expected = [
            'BAR' => 'ok',
            'BAZ' => 'aha',
        ];

        self::assertSame($expected, $variables->toArray());
    }
}
