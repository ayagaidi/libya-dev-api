# Roadmap

## v0.1 — Core developer API
- [x] Locations bridge to Libya Locations v1.2.0
- [x] Phone normalization
- [x] Phone validation and operator-range metadata
- [x] Telecom operator endpoint
- [x] OpenAPI 3.1 + Swagger UI
- [x] Rate limiting, caching, CORS
- [x] Tests + CI

## v0.2 — Location developer experience
- municipality map points and GeoJSON proxy endpoints
- bounding-box / nearby helpers where source quality permits
- conditional requests / ETags for public datasets
- SDK snippets generated from OpenAPI

## v0.3 — Libya business primitives
- bank directory from maintainable official sources
- public-holiday module with source/year metadata
- currency/IBAN helpers only where official validation rules are available

## Later
- postal/address normalization if a reliable source exists
- Laravel package client
- TypeScript SDK
- Dart/Flutter SDK
- Python client
- hosted public deployment and uptime/status page
