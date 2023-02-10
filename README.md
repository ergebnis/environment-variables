# environment-variables

[![Integrate](https://github.com/ergebnis/environment-variables/workflows/Integrate/badge.svg)](https://github.com/ergebnis/environment-variables/actions)
[![Merge](https://github.com/ergebnis/environment-variables/workflows/Merge/badge.svg)](https://github.com/ergebnis/environment-variables/actions)
[![Release](https://github.com/ergebnis/environment-variables/workflows/Release/badge.svg)](https://github.com/ergebnis/environment-variables/actions)
[![Renew](https://github.com/ergebnis/environment-variables/workflows/Renew/badge.svg)](https://github.com/ergebnis/environment-variables/actions)

[![Code Coverage](https://codecov.io/gh/ergebnis/environment-variables/branch/main/graph/badge.svg)](https://codecov.io/gh/ergebnis/environment-variables)
[![Type Coverage](https://shepherd.dev/github/ergebnis/environment-variables/coverage.svg)](https://shepherd.dev/github/ergebnis/environment-variables)

[![Latest Stable Version](https://poser.pugx.org/ergebnis/environment-variables/v/stable)](https://packagist.org/packages/ergebnis/environment-variables)
[![Total Downloads](https://poser.pugx.org/ergebnis/environment-variables/downloads)](https://packagist.org/packages/ergebnis/environment-variables)
[![Monthly Downloads](http://poser.pugx.org/ergebnis/environment-variables/d/monthly)](https://packagist.org/packages/ergebnis/environment-variables)

Provides an abstraction of environment variables.

## Installation

Run

```sh
composer require ergebnis/environment-variables
```

## Usage

This package provides the interface [`Ergebnis\Environment\Variables`](src/Variables.php) along with the following production implementations:

- [`Ergebnis\Environment\SystemVariables`](#ergebnisenvironmentsystemvariables)

This package also provides the following test implementations:

- [`Ergebnis\Environment\FakeVariables`](#ergebnisenvironmentfakevariables)
- [`Ergebnis\Environment\ReadOnlyVariables`](#ergebnisenvironmentreadonlyvariables)
- [`Ergebnis\Environment\TestVariables`](#ergebnisenvironmenttestvariables)

### Production Implementation

#### `Ergebnis\Environment\SystemVariables`

If you want to read, set, and unset environment variables in an object-oriented way in a production environment, you can use [`Ergebnis\Environment\SystemVariables`](src/SystemVariables.php):

```php
<?php

declare(strict_types=1);

use Ergebnis\Environment;

final class BuildEnvironment
{
    private $environmentVariables;

    public function __construct(Environment\Variables $environmentVariables)
    {
        $this->environmentVariables = $environmentVariables;
    }

    public function isGitHubActions(): bool
    {
        return $this->environmentVariables->has('GITHUB_ACTIONS')
            && 'true' === $this->environmentVariables->get('GITHUB_ACTIONS');
    }

    public function isTravisCi(): bool
    {
        return $this->environmentVariables->has('TRAVIS')
            && 'true' === $this->environmentVariables->get('TRAVIS');
    }
}
```
### Test Implementation

#### `Ergebnis\Environment\FakeVariables`

If you want to read, set, and unset environment variables in an object-oriented way in a test environment, but do not actually want to modify system environment variables, you can use [`Ergebnis\Environment\FakeVariables`](src/FakeVariables.php) as a test-double:

```php
<?php

declare(strict_types=1);

use Ergebnis\Environment;
use PHPUnit\Framework;

final class BuildEnvironmentTest extends Framework\TestCase
{
    public function testIsGitHubActionsReturnsFalseWhenNoneOfTheExpectedEnvironmentVariablesAreAvailable(): void
    {
        $environmentVariables = Environment\FakeVariables::empty();

        $buildEnvironment = new BuildEnvironment($environmentVariables);

        self::assertFalse($buildEnvironment->isGitHubActions());
    }

    public function testIsGitHubActionsReturnsFalseWhenValueOfGitHubActionsEnvironmentVariableIsNotTrue(): void
    {
        $environmentVariables = Environment\FakeVariables::fromArray([
            'GITHUB_ACTIONS' => 'false',
        ]);

        $buildEnvironment = new BuildEnvironment($environmentVariables);

        self::assertFalse($buildEnvironment->isGitHubActions());
    }

    public function testIsGitHubActionsReturnsTrueWhenValueOfGitHubActionsEnvironmentVariableIsTrue(): void
    {
        $environmentVariables = Environment\FakeVariables::fromArray([
            'GITHUB_ACTIONS' => 'true',
        ]);

        $buildEnvironment = new BuildEnvironment($environmentVariables);

        self::assertTrue($buildEnvironment->isGitHubActions());
    }
}
```
#### `Ergebnis\Environment\ReadOnlyVariables`

If you want to read environment variables in an object-oriented way in a test environment, but neither actually want to modify system environment variables, nor allow modification by the system under test, you can use [`Ergebnis\Environment\ReadOnlyVariables`](src/ReadOnlyVariables.php) as a test-double:

```php
<?php

declare(strict_types=1);

use Ergebnis\Environment;
use PHPUnit\Framework;

final class BuildEnvironmentTest extends Framework\TestCase
{
    public function testIsGitHubActionsReturnsFalseWhenNoneOfTheExpectedEnvironmentVariablesAreAvailable(): void
    {
        $environmentVariables = Environment\ReadOnlyVariables::empty();

        $buildEnvironment = new BuildEnvironment($environmentVariables);

        self::assertFalse($buildEnvironment->isGitHubActions());
    }

    public function testIsGitHubActionsReturnsFalseWhenValueOfGitHubActionsEnvironmentVariableIsNotTrue(): void
    {
        $environmentVariables = Environment\ReadOnlyVariables::fromArray([
            'GITHUB_ACTIONS' => 'false',
        ]);

        $buildEnvironment = new BuildEnvironment($environmentVariables);

        self::assertFalse($buildEnvironment->isGitHubActions());
    }

    public function testIsGitHubActionsReturnsTrueWhenValueOfGitHubActionsEnvironmentVariableIsTrue(): void
    {
        $environmentVariables = Environment\ReadOnlyVariables::fromArray([
            'GITHUB_ACTIONS' => 'true',
        ]);

        $buildEnvironment = new BuildEnvironment($environmentVariables);

        self::assertTrue($buildEnvironment->isGitHubActions());
    }
}
```

:bulb: The `ReadOnlyVariables` implementation will throw a [`ShouldNotBeUsed`](src/Exception/ShouldNotBeUsed.php) exception when the system under tests uses any of the following methods:

- `set()`
- `unset()`

#### `Ergebnis\Environment\TestVariables`

If your tests depend on environment variables, you have the following challenges:

- when you modify environment variables in a test, you want to restore environment variables that have existed before the test run to their original values
- when you modify environment variables in a test that has not been backed up before, and forget to restore it, it might affect other tests

To solve this problem, you can add the [`@backupGlobals`](https://phpunit.readthedocs.io/en/9.0/annotations.html#backupglobals) annotation to your test cases when using [`phpunit/phpunit`](https://github.com/sebastianbergmann/phpunit), or use [`Ergebnis\Environment\TestVariables`](src/TestVariables.php):

```php
<?php

declare(strict_types=1);

use Ergebnis\Environment;
use PHPUnit\Framework;

final class FooTest extends Framework\TestCase
{
    /**
     * @var Environment\TestVariables
     */
    private static $environmentVariables;

    protected function setUp() : void
    {
        // will back up environment variables FOO, BAR, and BAZ
        self::$environmentVariables = Environment\TestVariables::backup(
            'FOO',
            'BAR',
            'BAZ'
        );
    }

    protected function tearDown() : void
    {
        // will restore backed-up environment variables FOO, BAR, and BAZ to their initial state
        self::$environmentVariables->restore();
    }

    public function testSomethingThatDependsOnEnvironmentVariableFooToBeSet(): void
    {
        self::$environmentVariables->set(
            'FOO',
            '9000'
        );

        // ...
    }

    public function testSomethingThatDependsOnEnvironmentVariableFooToBeUnset(): void
    {
        self::$environmentVariables->unset('FOO');

        // ...
    }

    public function testSomethingThatDependsOnEnvironmentVariableQuxToBeSet(): void
    {
        // will throw exception because the environment variable QUX has not been backed up
        self::$environmentVariables->set(
            'QUX',
            '9000'
        );

        // ...
    }

    public function testSomethingThatDependsOnEnvironmentVariableQuxToBeUnset(): void
    {
        // will throw exception because the environment variable QUX has not been backed up
        self::$environmentVariables->unset('QUX');
    }
}
```

## Changelog

Please have a look at [`CHANGELOG.md`](CHANGELOG.md).

## Contributing

Please have a look at [`CONTRIBUTING.md`](.github/CONTRIBUTING.md).

## Code of Conduct

Please have a look at [`CODE_OF_CONDUCT.md`](https://github.com/ergebnis/.github/blob/master/CODE_OF_CONDUCT.md).

## License

This package is licensed using the MIT License.

Please have a look at [`LICENSE.md`](LICENSE.md).

## Curious what I am up to?

Follow me on [Twitter](https://twitter.com/intent/follow?screen_name=localheinz)!
