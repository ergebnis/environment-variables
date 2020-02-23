# environment-variables

[![Continuous Deployment](https://github.com/ergebnis/environment-variables/workflows/Continuous%20Deployment/badge.svg)](https://github.com/ergebnis/environment-variables/actions)
[![Continuous Integration](https://github.com/ergebnis/environment-variables/workflows/Continuous%20Integration/badge.svg)](https://github.com/ergebnis/environment-variables/actions)

[![Code Coverage](https://codecov.io/gh/ergebnis/environment-variables/branch/master/graph/badge.svg)](https://codecov.io/gh/ergebnis/environment-variables)
[![Type Coverage](https://shepherd.dev/github/ergebnis/environment-variables/coverage.svg)](https://shepherd.dev/github/ergebnis/environment-variables)

[![Latest Stable Version](https://poser.pugx.org/ergebnis/environment-variables/v/stable)](https://packagist.org/packages/ergebnis/environment-variables)
[![Total Downloads](https://poser.pugx.org/ergebnis/environment-variables/downloads)](https://packagist.org/packages/ergebnis/environment-variables)

Provides an abstraction of environment variables.

## Installation

Run

```
$ composer require ergebnis/environment-variables
```

## Usage

This package provides the interface [`Ergebnis\Environment\Variables`](src/Variables.php) along with the following production implementations:

- [`Ergebnis\Environment\SystemVariables`](#ergebnisenvironmentsystemvariables)

This package also provides the following test implementations:

- [`Ergebnis\Environment\FakeVariables`](#ergebnisenvironmentfakevariables)
- [`Ergebnis\Environment\ReadOnlyVariables`](#ergebnisenvironmentreadonlyvariables)

This package also provides a helper when you actually need to backup, modify, and restore environment variables in tests:

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
        $environmentVariables = new Environment\FakeVariables();

        $buildEnvironment = new BuildEnvironment($environmentVariables);

        self::assertFalse($buildEnvironment->isGitHubActions());
    }

    public function testIsGitHubActionsReturnsFalseWhenValueOfGitHubActionsEnvironmentVariableIsNotTrue(): void
    {
        $environmentVariables = new Environment\FakeVariables([
            'GITHUB_ACTIONS' => 'false',
        ]);

        $buildEnvironment = new BuildEnvironment($environmentVariables);

        self::assertFalse($buildEnvironment->isGitHubActions());
    }

    public function testIsGitHubActionsReturnsTrueWhenValueOfGitHubActionsEnvironmentVariableIsTrue(): void
    {
        $environmentVariables = new Environment\FakeVariables([
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
        $environmentVariables = new Environment\ReadOnlyVariables();

        $buildEnvironment = new BuildEnvironment($environmentVariables);

        self::assertFalse($buildEnvironment->isGitHubActions());
    }

    public function testIsGitHubActionsReturnsFalseWhenValueOfGitHubActionsEnvironmentVariableIsNotTrue(): void
    {
        $environmentVariables = new Environment\ReadOnlyVariables([
            'GITHUB_ACTIONS' => 'false',
        ]);

        $buildEnvironment = new BuildEnvironment($environmentVariables);

        self::assertFalse($buildEnvironment->isGitHubActions());
    }

    public function testIsGitHubActionsReturnsTrueWhenValueOfGitHubActionsEnvironmentVariableIsTrue(): void
    {
        $environmentVariables = new Environment\ReadOnlyVariables([
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

### Test Utility

#### `Ergebnis\Environment\TestVariables`

If your tests depend on environment variables, you have the following challenges:

- when you modify environment variables in a test, you want to restore environment variables that have existed before the test run to their original values
- when you modify environment variables in a test that has not been backed up before, and forget to restore it, it might affect other tests

To solve this problem, you can use [`Ergebnis\Environment\TestVariables`](src/TestVariables.php):

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
        self::$environmentVariables = Environment\Test::backup(
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
        self::$environmentVariables->set([
            'FOO' => '9000',
        ]);

        // ...
    }

    public function testSomethingThatDependsOnEnvironmentVariableBarToBeSet(): void
    {
        // will throw exception because a value for an environment variable needs to be a string or false
        self::$environmentVariables->set([
            'BAR' => null,
        ]);

        // ...
    }

    public function testSomethingThatDependsOnDynamicEnvironmentVariableToBeSet(): void
    {
        // will throw exception when $name is not a string, or an empty string, or an untrimmed string
        self::$environmentVariables->set([
            $name => '9000',
        ]);

        // ...
    }

    public function testSomethingThatDependsOnEnvironmentVariableQuxToBeSet(): void
    {
        // will throw exception because the environment variable QUX has not been backed up
        self::$environmentVariables->set([
            'QUX' => '9000',
        ]);

        // ...
    }

    public function testSomethingThatDependsOnEnvironmentVariableQuxToBeSet(): void
    {
        // will throw exception because the environment variable QUX has not been backed up
        self::$environmentVariables->set([
            'QUX' => '9000',
        ]);

        // ...
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
