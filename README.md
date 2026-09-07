<p align="center">
  <img src="docs/assets/libya-dev-api-banner.svg" alt="Libya Dev API banner" width="100%">
</p>

# Libya Dev API 🇱🇾

> **The open developer API for Libya.** One stable HTTP/JSON interface for Libya-specific locations, Geo helpers, phone normalization, telecom metadata, banks, public holidays, official exchange rates and business-day helpers.

[![Release](https://img.shields.io/github/v/release/ayagaidi/libya-dev-api?label=release)](https://github.com/ayagaidi/libya-dev-api/releases/latest)
[![Laravel Quality](https://github.com/ayagaidi/libya-dev-api/actions/workflows/tests.yml/badge.svg)](https://github.com/ayagaidi/libya-dev-api/actions/workflows/tests.yml)
[![SDK Quality](https://github.com/ayagaidi/libya-dev-api/actions/workflows/sdk-quality.yml/badge.svg)](https://github.com/ayagaidi/libya-dev-api/actions/workflows/sdk-quality.yml)
[![License](https://img.shields.io/github/license/ayagaidi/libya-dev-api)](LICENSE)

**Laravel 13 · REST · OpenAPI 3.1 · GeoJSON · JavaScript · Python · Dart/Flutter · Tests · CI**

Libya Dev API is API-first: Laravel powers the backend, but consumers do **not** need PHP or Laravel. Any language that can make an HTTP request can integrate it.

## Current scope

- Libyan municipalities and cities through pinned `Libya Locations v1.2.0`
- **municipality point + GeoJSON endpoints** with source provenance
- **nearest and nearby municipality helpers** using Haversine distance
- null coordinates remain null instead of being guessed
- Libyan phone normalization and operator-range validation
- telecom operator metadata with source provenance
- 26-bank commercial bank directory from the Central Bank of Libya
- Libya official-holiday calendar with annual confirmation rules
- live official Central Bank of Libya exchange rates + currency conversion
- public-sector business-day helpers
- **source SDKs for JavaScript, Python and Dart/Flutter**
- OpenAPI 3.1 + Swagger UI
- Laravel and SDK CI quality gates

## API endpoints

| Method | Endpoint | Purpose |
|---|---|---|
| GET | `/api/v1/meta` | API + Libya metadata |
| GET | `/api/v1/locations/municipalities` | municipalities, optional `?q=` |
| GET | `/api/v1/locations/municipalities/{slug}` | municipality by stable slug |
| GET | `/api/v1/locations/cities` | cities/places, optional `?q=` |
| GET | `/api/v1/geo/municipality-points` | municipality point dataset; optional `?mapped_only=1` |
| GET | `/api/v1/geo/municipalities.geojson` | pinned point GeoJSON |
| GET | `/api/v1/geo/nearest?lat=...&lng=...` | nearest mapped municipalities |
| GET | `/api/v1/geo/nearby?lat=...&lng=...&radius_km=...` | mapped municipalities within radius |
| POST | `/api/v1/phone/normalize` | normalize a Libyan mobile number |
| POST | `/api/v1/phone/validate` | validate format + known operator range |
| GET | `/api/v1/telecom/operators` | operator prefixes + provenance |
| GET | `/api/v1/banks` | commercial banks, optional `?q=` / `?city=` |
| GET | `/api/v1/banks/{slug}` | bank by stable slug |
| GET | `/api/v1/holidays/{year}` | holiday calendar |
| GET | `/api/v1/exchange-rates` | current official CBL exchange rates |
| GET | `/api/v1/exchange-rates/{currency}` | one rate by ISO currency code |
| POST | `/api/v1/currency/convert` | convert using `average`, `buy` or `sell` |
| GET | `/api/v1/calendar/is-business-day?date=YYYY-MM-DD` | business-day analysis |
| GET | `/api/v1/calendar/next-business-day?date=YYYY-MM-DD` | next business day |
| GET | `/api/v1/calendar/business-days?from=...&to=...` | inclusive range up to 366 days |
| GET | `/openapi.json` | OpenAPI contract |
| GET | `/docs` | Swagger UI |

## Geo example

```bash
curl 'http://localhost:8000/api/v1/geo/nearest?lat=32.8872&lng=13.1913&limit=3'
```

The Geo API uses reference points from the pinned Libya Locations release. Distance is calculated with the Haversine formula. It does not invent coordinates for municipalities that are still unmapped.

## SDKs

Source SDKs live in [`sdks/`](sdks/README.md). Registry publication is intentionally separate; until a registry package is linked here, use the tagged GitHub source.

### JavaScript

```js
import { LibyaDevApi } from 'libya-dev-api-client';

const api = new LibyaDevApi({baseUrl: 'https://your-host.ly/api/v1'});
const result = await api.nearestMunicipalities(32.8872, 13.1913);
```

### Python

```python
from libya_dev_api import LibyaDevApi

api = LibyaDevApi('https://your-host.ly/api/v1')
result = api.nearest_municipalities(32.8872, 13.1913)
```

### Dart / Flutter

```dart
final api = LibyaDevApi(baseUrl: 'https://your-host.ly/api/v1');
final result = await api.nearestMunicipalities(32.8872, 13.1913);
```

See [`sdks/README.md`](sdks/README.md) for source installation instructions.

## Install the API

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan serve
```

Then open `http://localhost:8000/docs`.

## Data provenance

Locations and Geo data are read from the pinned `Libya Locations v1.2.0` release. Telecom metadata carries verification strength. Banks and exchange rates are based on Central Bank of Libya sources. Holidays distinguish fixed statutory dates from annual confirmations. Business-day helpers combine the public-sector workweek with the holiday module.

See [`DATA_SOURCES.md`](DATA_SOURCES.md).

## Design principles

1. **No guessed Libya-specific facts.** Missing or uncertain data is labelled, not invented.
2. **Stable versioned API.** Breaking changes require a new API version.
3. **Language agnostic.** HTTP + JSON + OpenAPI are the contract.
4. **Source provenance.** Country-specific metadata should point to its source.
5. **Small core, useful modules.** New primitives are added only when a maintainable source and clear developer use case exist.

## Roadmap

See [`ROADMAP.md`](ROADMAP.md).

## Security

See [`SECURITY.md`](SECURITY.md). Do not report suspected vulnerabilities in a public issue.

## License

MIT. See [`LICENSE`](LICENSE).

Maintained by **Aya Aljaidi** — building open developer infrastructure for Libya. 🇱🇾
