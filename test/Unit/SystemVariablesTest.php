<?php

declare(strict_types=1);

/**
 * Copyright (c) 2020-2024 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE.md file that was distributed with this source code.
 *
 * @see https://github.com/ergebnis/environment-variables
 */

namespace Ergebnis\Environment\Test\Unit;

use Ergebnis\Environment\Exception;
use Ergebnis\Environment\SystemVariables;
use Ergebnis\Environment\Test;
use Ergebnis\Environment\TestVariables;
use PHPUnit\Framework;

#[Framework\Attributes\CoversClass(SystemVariables::class)]
#[Framework\Attributes\UsesClass(Exception\InvalidName::class)]
#[Framework\Attributes\UsesClass(Exception\NotSet::class)]
#[Framework\Attributes\UsesClass(TestVariables::class)]
final class SystemVariablesTest extends Framework\TestCase
{
    use Test\Util\Helper;
    private const NAME = 'FOO';
    private static TestVariables $environmentVariables;

    protected function setUp(): void
    {
        self::$environmentVariables = TestVariables::backup(self::NAME);

        \putenv(self::NAME);
    }

    protected function tearDown(): void
    {
        self::$environmentVariables->restore();
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\Name::class, 'invalidValue')]
    public function testHasThrowsInvalidNameWhenNameIsInvalid(string $name): void
    {
        $variables = new SystemVariables();

        $this->expectException(Exception\InvalidName::class);

        $variables->has($name);
    }

    public function testHasReturnsFalseWhenEnvironmentVariableIsNotSet(): void
    {
        $variables = new SystemVariables();

        self::assertFalse($variables->has(self::NAME));
    }

    public function testHasReturnsTrueWhenEnvironmentVariableIsSet(): void
    {
        \putenv(\sprintf(
            '%s=%s',
            self::NAME,
            self::faker()->sentence(),
        ));

        $variables = new SystemVariables();

        self::assertTrue($variables->has(self::NAME));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\Name::class, 'invalidValue')]
    public function testGetThrowsInvalidNameWhenNameIsInvalid(string $name): void
    {
        $variables = new SystemVariables();

        $this->expectException(Exception\InvalidName::class);

        $variables->get($name);
    }

    public function testGetThrowsNotSetFalseWhenEnvironmentVariableIsNotSet(): void
    {
        $variables = new SystemVariables();

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

        $variables = new SystemVariables();

        self::assertSame($value, $variables->get(self::NAME));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\Name::class, 'invalidValue')]
    public function testSetThrowsInvalidNameWhenNameIsInvalid(string $name): void
    {
        $value = self::faker()->sentence();

        $variables = new SystemVariables();

        $this->expectException(Exception\InvalidName::class);

        $variables->set(
            $name,
            $value,
        );
    }

    public function testSetSetsValue(): void
    {
        $value = self::faker()->sentence();

        $variables = new SystemVariables();

        $variables->set(
            self::NAME,
            $value,
        );

        self::assertSame($value, \getenv(self::NAME));
    }

    #[Framework\Attributes\DataProviderExternal(Test\DataProvider\Name::class, 'invalidValue')]
    public function testUnsetThrowsInvalidNameWhenNameIsInvalid(string $name): void
    {
        $variables = new SystemVariables();

        $this->expectException(Exception\InvalidName::class);

        $variables->unset($name);
    }

    public function testUnsetUnsetsVariable(): void
    {
        $value = self::faker()->sentence();

        \putenv(\sprintf(
            '%s=%s',
            self::NAME,
            $value,
        ));

        $variables = new SystemVariables();

        $variables->unset(self::NAME);

        self::assertFalse(\getenv(self::NAME));
    }

    public function testToArrayReturnsCurrentValues(): void
    {
        $variables = new SystemVariables();

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

        $variables = new SystemVariables();

        self::assertSame(\getenv(), $variables->toArray());
    }

    public function testToArrayReturnsValuesWhenValueHasBeenUnset(): void
    {
        \putenv(self::NAME);

        $variables = new SystemVariables();

        self::assertSame(\getenv(), $variables->toArray());
    }
}
