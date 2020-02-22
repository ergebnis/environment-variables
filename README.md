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

### `Ergebnis\Environment\Variables\Production`

If you want to read, set, and unset environment variables in an object-oriented way in a production environment, you can use [`Ergebnis\Environment\Variables\Production`](src/Production.php):

```php
use Ergebnis\Environment\Variables;

final class BuildEnvironment
{
    private $environmentVariables;

    public function __construct(Variables\Production $environmentVariables)
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

### `Ergebnis\Environment\Variables\Test`

If your tests depend on environment variables, you have the following challenges:

- when you modify environment variables in a test, you want to restore environment variables that have existed before the test run to their original values
- when you modify environment variables in a test that has not been backed up before, and forget to restore it, it might affect other tests

To solve this problem, you can use [`Ergebnis\Environment\Variables\Test`](src/Test.php):

```php
use Ergebnis\Environment\Variables;
use PHPUnit\Framework;

final class FooTest extends Framework\TestCase
{
    /**
     * @var Variables\Test
     */
    private static $environmentVariables;

    protected function setUp() : void
    {
        // will back up environment variables FOO, BAR, and BAZ
        self::$environmentVariables = Variables\Test::backup(
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
