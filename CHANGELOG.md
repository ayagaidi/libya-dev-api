# Changelog

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
- sourced 2026 confirmations for Eid al-Fitr, Arafah/Eid al-Adha, Hijri New Year and the Prophet's Birthday
- explicit `requires_annual_confirmation` handling for future religious dates
- bank and holiday OpenAPI documentation
- feature tests for bank search/filtering and holiday integrity

## [0.1.0] - 2026-09-07

### Added
- API-first Laravel 13 foundation
- versioned `/api/v1` routes
- pinned Libya Locations v1.2.0 integration
- municipality/city search in Arabic, English and stable slugs
- Libyan mobile normalization and validation
- telecom operator prefix metadata with source provenance and verification strength
- OpenAPI 3.1 contract + Swagger UI
- public CORS, caching and API rate limiting
- structured source-unavailable responses for pinned location data
- Arabic project documentation and contribution/data-source guidance
- committed `composer.lock` for reproducible installs
- PHPUnit feature suite + Laravel Pint CI
