# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased

For a full diff see [`1.6.0...main`][1.6.0...main].

### Changed

- Allowed installation on PHP 8.5 ([#865]), by [@localheinz]

## [`1.6.0`][1.6.0]

For a full diff see [`1.5.0...1.6.0`][1.5.0...1.6.0].

### Added

- Added support for PHP 8.4 ([#823]), by [@localheinz]

## [`1.5.0`][1.5.0]

For a full diff see [`1.4.0...1.5.0`][1.4.0...1.5.0].

### Added

- Added support for PHP 8.3 ([#646]), by [@localheinz]

### Changed

- Dropped support for PHP 8.0 ([#553]), by [@localheinz]

## [`1.4.0`][1.4.0]

For a full diff see [`1.3.0...1.4.0`][1.3.0...1.4.0].

### Changed

- Dropped support for PHP 7.4 ([#463]), by [@localheinz]

## [`1.3.0`][1.3.0]

For a full diff see [`1.2.0...1.3.0`][1.2.0...1.3.0].

### Changed

- Dropped support for PHP 7.3 ([#337]), by [@localheinz]

## [`1.2.0`][1.2.0]

For a full diff see [`1.1.0...1.2.0`][1.1.0...1.2.0].

### Changed

- Dropped support for PHP 7.2 ([#326]), by [@localheinz]

## [`1.1.0`][1.1.0]

For a full diff see [`1.0.0...1.1.0`][1.0.0...1.1.0].

### Added

- Added support for PHP 8.0 ([#176]), by [@localheinz]

## [`1.0.0`][1.0.0]

For a full diff see [`c0c63bb...1.0.0`][c0c63bb...1.0.0].

### Added

- Added `Test`, which allows backing up, restoring, and safely modifying environment variables in test environments ([#1]), by [@localheinz]
- Added `Production`, which allows reading, setting, and unsetting environment variables in production environments ([#2]), by [@localheinz]
- Added `FakeVariables`, which can be used as a fake implementation of `Variables` in test environments ([#7]), by [@localheinz]
- Added `ReadOnlyVariables`, which can be used as a mock implementation of `Variables` in test environments ([#8]), by [@localheinz]
- Added `toArray()` to `Ergebnis\Environment\Variables` ([#18]), by [@localheinz]

### Changed

- Renamed namespace `Ergebnis\Environment\Variables` to `Ergebnis\Environment` ([#5]), by [@localheinz]
- Renamed `Ergebnis\Environment\Production` to `Ergebnis\Environment\SystemVariables` ([#6]), by [@localheinz]
- Renamed `Ergebnis\Environment\Test` to `Ergebnis\Environment\TestVariables` ([#9]), by [@localheinz]
- Started throwing `Ergebnis\Environment\Exception\CouldNotSet` when a system environment variable could not be set ([#14]), by [@localheinz]
- Started throwing `Ergebnis\Environment\Exception\CouldNotUnset` when a system environment variable could not be unset ([#15]), by [@localheinz]
- Started throwing `Ergebnis\Environment\Exception\NotSet` when attempting to retrieve the value of an environment variable that is not set ([#16]), by [@localheinz]
- Adjusted `Ergebnis\Environment\TestVariables` so it implements the `Ergebnis\Environment\Variables` interface as well ([#17]), by [@localheinz]
- Extracted named constructors `Ergebnis\Environment\FakeVariables::empty()` and `Ergebnis\Environment\FakeVariables::fromArray()` ([#19]), by [@localheinz]
- Extracted named constructors `Ergebnis\Environment\ReadOnlyVariables::empty()` and `Ergebnis\Environment\ReadOnlyVariables::fromArray()` ([#20]), by [@localheinz]

[1.0.0]: https://github.com/ergebnis/environment/variables/releases/tag/1.0.0
[1.1.0]: https://github.com/ergebnis/environment/variables/releases/tag/1.1.0
[1.2.0]: https://github.com/ergebnis/environment/variables/releases/tag/1.2.0
[1.3.0]: https://github.com/ergebnis/environment/variables/releases/tag/1.3.0
[1.4.0]: https://github.com/ergebnis/environment/variables/releases/tag/1.4.0
[1.5.0]: https://github.com/ergebnis/environment/variables/releases/tag/1.5.0
[1.6.0]: https://github.com/ergebnis/environment/variables/releases/tag/1.6.0

[c0c63bb...1.0.0]: https://github.com/ergebnis/environment-variables/compare/c0c63bb...1.0.0
[1.0.0...1.1.0]: https://github.com/ergebnis/environment-variables/compare/1.0.0...1.1.0
[1.1.0...1.2.0]: https://github.com/ergebnis/environment-variables/compare/1.1.0...1.2.0
[1.2.0...1.3.0]: https://github.com/ergebnis/environment-variables/compare/1.2.0...1.3.0
[1.3.0...1.4.0]: https://github.com/ergebnis/environment-variables/compare/1.3.0...1.4.0
[1.4.0...1.5.0]: https://github.com/ergebnis/environment-variables/compare/1.4.0...1.5.0
[1.5.0...1.6.0]: https://github.com/ergebnis/environment-variables/compare/1.5.0...1.6.0
[1.6.0...main]: https://github.com/ergebnis/environment-variables/compare/1.6.0...main

[#1]: https://github.com/ergebnis/environment-variables/pull/1
[#2]: https://github.com/ergebnis/environment-variables/pull/2
[#5]: https://github.com/ergebnis/environment-variables/pull/5
[#6]: https://github.com/ergebnis/environment-variables/pull/6
[#7]: https://github.com/ergebnis/environment-variables/pull/7
[#8]: https://github.com/ergebnis/environment-variables/pull/8
[#9]: https://github.com/ergebnis/environment-variables/pull/9
[#14]: https://github.com/ergebnis/environment-variables/pull/14
[#15]: https://github.com/ergebnis/environment-variables/pull/15
[#16]: https://github.com/ergebnis/environment-variables/pull/16
[#17]: https://github.com/ergebnis/environment-variables/pull/17
[#18]: https://github.com/ergebnis/environment-variables/pull/18
[#19]: https://github.com/ergebnis/environment-variables/pull/19
[#20]: https://github.com/ergebnis/environment-variables/pull/20
[#176]: https://github.com/ergebnis/environment-variables/pull/176
[#326]: https://github.com/ergebnis/environment-variables/pull/326
[#337]: https://github.com/ergebnis/environment-variables/pull/337
[#463]: https://github.com/ergebnis/environment-variables/pull/463
[#553]: https://github.com/ergebnis/environment-variables/pull/553
[#646]: https://github.com/ergebnis/environment-variables/pull/646
[#823]: https://github.com/ergebnis/environment-variables/pull/823
[#865]: https://github.com/ergebnis/environment-variables/pull/865

[@localheinz]: https://github.com/localheinz
