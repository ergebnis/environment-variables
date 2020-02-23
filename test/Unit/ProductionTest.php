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
use Ergebnis\Environment\Production;
use Ergebnis\Environment\Test;
use Ergebnis\Test\Util\Helper;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Ergebnis\Environment\Production
 *
 * @uses \Ergebnis\Environment\Exception\InvalidName
 * @uses \Ergebnis\Environment\Test
 */
final class ProductionTest extends Framework\TestCase
{
    use Helper;

    private const NAME = 'FOO';

    /**
     * @var Test
     */
    private static $environmentVariables;

    protected function setUp(): void
    {
        self::$environmentVariables = Test::backup(self::NAME);

        \putenv(self::NAME);
    }

    protected function tearDown(): void
    {
        self::$environmentVariables->restore();
    }

    /**
     * @dataProvider \Ergebnis\Environment\Test\DataProvider\Name::invalid()
     *
     * @param string $name
     */
    public function testHasThrowsInvalidNameWhenNameIsInvalid(string $name): void
    {
        $variables = new Production();

        $this->expectException(Exception\InvalidName::class);

        $variables->has($name);
    }

    public function testHasReturnsFalseWhenEnvironmentVariableIsNotSet(): void
    {
        $variables = new Production();

        self::assertFalse($variables->has(self::NAME));
    }

    public function testHasReturnsTrueWhenEnvironmentVariableIsSet(): void
    {
        \putenv(\sprintf(
            '%s=%s',
            self::NAME,
            self::faker()->sentence
        ));

        $variables = new Production();

        self::assertTrue($variables->has(self::NAME));
    }

    /**
     * @dataProvider \Ergebnis\Environment\Test\DataProvider\Name::invalid()
     *
     * @param string $name
     */
    public function testGetThrowsInvalidNameWhenNameIsInvalid(string $name): void
    {
        $variables = new Production();

        $this->expectException(Exception\InvalidName::class);

        $variables->get($name);
    }

    public function testGetReturnsFalseWhenEnvironmentVariableIsNotSet(): void
    {
        $variables = new Production();

        self::assertFalse($variables->get(self::NAME));
    }

    public function testGetReturnsValueWhenEnvironmentVariableIsSet(): void
    {
        $value = self::faker()->sentence;

        \putenv(\sprintf(
            '%s=%s',
            self::NAME,
            $value
        ));

        $variables = new Production();

        self::assertSame($value, $variables->get(self::NAME));
    }

    /**
     * @dataProvider \Ergebnis\Environment\Test\DataProvider\Name::invalid()
     *
     * @param string $name
     */
    public function testSetThrowsInvalidNameWhenNameIsInvalid(string $name): void
    {
        $value = self::faker()->sentence;

        $variables = new Production();

        $this->expectException(Exception\InvalidName::class);

        $variables->set(
            $name,
            $value
        );
    }

    public function testSetSetsValue(): void
    {
        $value = self::faker()->sentence;

        $variables = new Production();

        $variables->set(
            self::NAME,
            $value
        );

        self::assertSame($value, \getenv(self::NAME));
    }

    /**
     * @dataProvider \Ergebnis\Environment\Test\DataProvider\Name::invalid()
     *
     * @param string $name
     */
    public function testUnsetThrowsInvalidNameWhenNameIsInvalid(string $name): void
    {
        $variables = new Production();

        $this->expectException(Exception\InvalidName::class);

        $variables->unset($name);
    }

    public function testUnsetUnsetsVariable(): void
    {
        $value = self::faker()->sentence;

        \putenv(\sprintf(
            '%s=%s',
            self::NAME,
            $value
        ));

        $variables = new Production();

        $variables->unset(self::NAME);

        self::assertFalse(\getenv(self::NAME));
    }
}
