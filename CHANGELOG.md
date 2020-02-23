# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased

For a full diff see [`c0c63bb...master`][c0c63bb...master].

### Added

* Added `Test`, which allows backing up, restoring, and safely modifying environment variables in test environments ([#1]), by [@localheinz]
* Added `Production`, which allows reading, setting, and unsetting environment variables in production environments ([#2]), by [@localheinz]
* Added `FakeVariables`, which can be used as a fake implementation of `Variables` in test environments ([#7]), by [@localheinz]

### Changed

* Renamed namespace `Ergebnis\Environment\Variables` to `Ergebnis\Environment` ([#5]), by [@localheinz]
* Renamed `Ergebnis\Environment\Production` to `Ergebnis\Environment\SystemVariables` ([#6]), by [@localheinz]

[c0c63bb...master]: https://github.com/ergebnis/environment-variables/compare/c0c63bb...master

[#1]: https://github.com/ergebnis/environment-variables/pull/1
[#2]: https://github.com/ergebnis/environment-variables/pull/2
[#5]: https://github.com/ergebnis/environment-variables/pull/5
[#6]: https://github.com/ergebnis/environment-variables/pull/6
[#7]: https://github.com/ergebnis/environment-variables/pull/7

[@localheinz]: https://github.com/localheinz
