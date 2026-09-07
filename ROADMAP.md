# Roadmap

## v0.1 — Core developer API
- [x] Locations bridge to Libya Locations v1.2.0
- [x] Phone normalization and validation
- [x] Telecom operator metadata
- [x] OpenAPI + Swagger
- [x] Rate limiting, caching, CORS
- [x] Tests + CI

## v0.2 — Libya business + holiday primitives
- [x] Central Bank commercial bank directory
- [x] Arabic/English bank search and stable slugs
- [x] statutory holiday definitions
- [x] sourced 2026 religious-holiday dates
- [x] no guessing for future unconfirmed religious dates

## v0.3 — Finance + business calendar
- [x] live official CBL exchange rates
- [x] buy/sell/average and normalized per-unit values
- [x] currency conversion
- [x] Sunday–Thursday general public-sector business calendar
- [x] next/range business-day helpers

## v0.4 — Geo + SDK developer experience
- [x] municipality point proxy from pinned Libya Locations release
- [x] GeoJSON endpoint
- [x] nearest municipality helper
- [x] nearby/radius helper
- [x] JavaScript source SDK + tests
- [x] Python source SDK + tests
- [x] Dart/Flutter source SDK + tests
- [x] independent SDK CI quality gate

## v0.5 — Hosted API + package distribution
- [x] production Docker image
- [x] Railway config-as-code + `/health`
- [x] container build/smoke-test CI
- [x] registry-ready JavaScript, Python and Dart package metadata
- [x] npm / PyPI / pub.dev publishing workflows
- [x] publishability checks in SDK CI
- [ ] public Railway deployment with stable HTTPS URL
- [ ] first npm publication
- [ ] first PyPI publication
- [ ] first pub.dev publication

## Next
- conditional requests / ETags for public datasets
- generated typed models from OpenAPI
- uptime/status page for hosted deployment
- TypeScript-first generated client once the API contract stabilizes further

## Later
- postal/address normalization only if a reliable source exists
- IBAN helpers only where official Libyan validation rules are available
- bank branch/routing metadata only with a maintainable primary source
