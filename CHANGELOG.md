# Changelog

## 1.1.3

### Added

- Added GA4 hostname filtering for shared properties and subdomains.
- Added automatic hostname detection from Geeklog `site_url` when the hostname setting is blank.
- Added `*` hostname mode to intentionally display the full GA4 property.
- Added clearer API, authorization, permission, quota and dependency errors.

### Changed

- Centralized runtime configuration trimming, normalization and validation.
- Active-user KPIs for Yesterday, Last 7 Days and Last 30 Days are now requested directly from GA4.
- Dashboard date ranges use completed days and exclude partial current-day data.
- Browser cache keys now include Property ID and hostname scope.
- JavaScript values are encoded with `json_encode()` instead of manual escaping.
- Pinned Chart.js to version 4.5.1.
- Replaced direct `window.onload` assignment with an event listener and dependency timeout.

### Fixed

- Fixed misleading multi-day active-user totals caused by adding daily unique-user counts.
- Fixed fragile Yesterday calculation based on row position.
- Fixed subdomains showing the same full-property dashboard when sharing one GA4 property.
- Fixed frontend Measurement ID use when configuration contained surrounding whitespace.
- Removed an unused legacy template-path helper that referenced an undefined local variable.

## 1.1.2

### Changed

- Extended official compatibility to Geeklog 2.1.1 or later.
- Extended official PHP compatibility to PHP 5.6 or later.
- Updated plugin homepage to the GitHub repository.
- Updated installation and compatibility documentation.

### Fixed

- Added missing GA4 Property ID configuration during plugin upgrades.
- Cleaned up legacy language documentation.
- Modernized legacy page rendering when access is denied.

### Removed

- Removed the obsolete legacy Google Analytics GData API library.
