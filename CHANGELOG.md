# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to Semantic Versioning (https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Changed
- Release workflow now automatically compiles PO files to MO before packaging

## [1.3.1] - 2026-06-05

### Changed
- Holiday and workday dates in settings now displayed as styled chips matching the rest of the plugin suite
- Updated translations for all supported locales (ru_RU, en_US, be_BY, kk_KZ, uk_UA)

## [1.3.0] - 2026-05-19

### Added
- New UI 2.0 settings screen built with Vue 3, TypeScript, and Vite
- Webasyst UI 2.0 components for enhanced admin interface
- Legacy settings screen UI preserved for backwards compatibility

### Changed
- Settings admin interface redesigned with modern Vue 3 architecture
- JavaScript build tooling migrated from Webpack to Vite
- Legacy JavaScript bundle minified for distribution

## [1.2.0] - 2023-02-17

### Added
- Currency selector in plugin settings
- Localization support for multiple languages: Russian (ru_RU), English (en_US), Belarusian (be_BY), Kazakh (kk_KZ), Ukrainian (uk_UA)
- Localized UI strings throughout the plugin and settings interface

### Changed
- Improved settings UI with language-specific interface labels
- Enhanced configuration flexibility for multi-currency stores

## [1.0.3] - 2022-07-20

### Added
- Support for fractional quantity values in shipping calculations

### Fixed
- Improved handling of weight and distance calculations with decimal values

## [1.0.2] - 2021-05-11

### Fixed
- Various bug fixes and stability improvements

## [1.0.1] - 2021-05-11

### Fixed
- Fixed `departure_datetime` type handling in order processing

## [1.0.0] - 2021-04-17

### Added
- Initial release of Zamkad shipping plugin
- Distance-based shipping cost calculation
- Geography-based restrictions (country and region limiting)
- Weight limits (minimum and maximum) for shipping calculation
- Price limits (minimum and maximum) for shipping calculation
- Delivery date and timeframe configuration
- Holidays and workdays settings
- Desired delivery date field support
- Street address field request capability
- Backend custom fields support
- Multiple language support (initial localization)
