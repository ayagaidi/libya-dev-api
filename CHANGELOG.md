# Changelog

## [0.4.0] - Unreleased

### Added
- municipality point endpoint backed by pinned Libya Locations v1.2.0 data
- GeoJSON proxy endpoint for municipality reference points
- nearest mapped municipality helper using Haversine distance
- nearby municipality radius search with bounded radius and result limits
- explicit mapped/total coverage metadata without guessing missing coordinates
- JavaScript source SDK with Node tests
- Python source SDK using the standard library with unittest coverage
- Dart/Flutter source SDK with HTTP client injection and tests
- independent SDK Quality GitHub Actions workflow
- Geo and SDK documentation in English and Arabic
- OpenAPI 0.4.0 Geo contract

## [0.3.0] - 2026-09-07

### Added
- live official Central Bank of Libya exchange-rate endpoint
- ISO currency mapping for supported CBL rows
- preservation of published quoted units plus normalized per-unit buy/sell/average values
- cache-backed last-good exchange-rate fallback with explicit stale metadata
- single-currency exchange-rate lookup
- LYD-aware currency converter using average, buy or sell columns
- general Libya public-sector business-day analysis
- next-business-day helper
- inclusive business-day range helper up to 366 days
- Sunday–Thursday workweek model with Friday/Saturday weekly rest
- provisional confidence when a future religious-holiday calendar is incomplete
- OpenAPI and source-provenance documentation for finance/calendar modules
- feature tests for exchange-rate parsing, multi-unit quotes, conversion, weekends, holidays and future-calendar confidence

## [0.2.0] - 2026-09-07

### Added
- source-backed commercial bank directory from the Central Bank of Libya
- 26 bank records with stable slugs, Arabic/English names, city and website
- bank search by Arabic/English name, city or stable slug
- bank lookup endpoint by slug
- public-holiday calendar based on Law No. 5 of 2012
- sourced 2026 confirmations for religious holidays
- explicit `requires_annual_confirmation` handling for future religious dates

## [0.1.0] - 2026-09-07

### Added
- API-first Laravel 13 foundation
- versioned `/api/v1` routes
- pinned Libya Locations v1.2.0 integration
- municipality/city search
- Libyan mobile normalization and validation
- telecom operator prefix metadata with provenance
- OpenAPI + Swagger UI
- rate limiting, caching and CORS
- PHPUnit + Laravel Pint CI
