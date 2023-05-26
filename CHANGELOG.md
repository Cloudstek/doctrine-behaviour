# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [2.1.0] - 2023-05-24
### Changed
- Allow DateTimeInterface in setters instead of just DateTime and DateTimeImmutable
- Update to PHPUnit 10.1

## [2.0.2] - 2022-06-29
### Added
- More documentation and examples
- Changelog

### Fixed
- Translation not being properly removed with setTranslations().

## [2.0.1] - 2022-06-29
### Added
- Translatable behaviour
- More tests
- Github action to automatically run tests

## [2.0.0] - 2022-06-15
### Changed
- Timestampable columns createdAt and updatedAt are no longer nullable.

## [1.0.1] - 2022-05-18
### Added
- Timestampable subscriber to automatically add entity listener to timestampable entities.

[Unreleased]: https://github.com/Cloudstek/doctrine-behaviour/compare/v2.1.0...develop
[2.1.0]: https://github.com/Cloudstek/doctrine-behaviour/compare/v2.0.2...v2.1.0
[2.0.2]: https://github.com/Cloudstek/doctrine-behaviour/compare/v2.0.1...v2.0.2
[2.0.1]: https://github.com/Cloudstek/doctrine-behaviour/compare/v2.0.0...v2.0.1
[2.0.0]: https://github.com/Cloudstek/doctrine-behaviour/compare/v1.0.1...v2.0.0
[1.0.1]: https://github.com/Cloudstek/doctrine-behaviour/compare/v1.0.0...v1.0.1
[1.0.0]: https://github.com/Cloudstek/doctrine-behaviour/releases/tag/v1.0.0
