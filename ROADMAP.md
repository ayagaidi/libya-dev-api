# Roadmap

## v0.1 — Core developer API
- [x] Locations bridge to Libya Locations v1.2.0
- [x] Phone normalization
- [x] Phone validation and operator-range metadata
- [x] Telecom operator endpoint
- [x] OpenAPI 3.1 + Swagger UI
- [x] Rate limiting, caching, CORS
- [x] Tests + CI

## v0.2 — Libya business + calendar primitives
- [x] commercial bank directory from the Central Bank of Libya
- [x] Arabic/English bank search and stable slugs
- [x] public-holiday definitions from Law No. 5 of 2012
- [x] sourced 2026 religious-holiday dates
- [x] explicit `requires_annual_confirmation` status instead of guessing future religious dates
- [x] OpenAPI and regression tests for banks/holidays

## v0.3 — Location developer experience
- municipality map points and GeoJSON proxy endpoints
- bounding-box / nearby helpers where source quality permits
- conditional requests / ETags for public datasets
- SDK snippets generated from OpenAPI

## v0.4 — More Libya developer primitives
- currency/IBAN helpers only where official validation rules are available
- bank branch/routing metadata only if a maintainable primary source exists
- government/administrative identifiers only where publication is appropriate and source-backed

## Later
- postal/address normalization if a reliable source exists
- Laravel package client
- TypeScript SDK
- Dart/Flutter SDK
- Python client
- hosted public deployment and uptime/status page
