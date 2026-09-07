<p align="center">
  <img src="docs/assets/libya-dev-api-banner.svg" alt="Libya Dev API banner" width="100%">
</p>

# Libya Dev API 🇱🇾

> **The open developer API for Libya.** One stable HTTP/JSON interface for Libya-specific locations, phone normalization, telecom metadata, banks and public holidays.

[![Release](https://img.shields.io/github/v/release/ayagaidi/libya-dev-api?label=release)](https://github.com/ayagaidi/libya-dev-api/releases/latest)
[![Laravel Quality](https://github.com/ayagaidi/libya-dev-api/actions/workflows/tests.yml/badge.svg)](https://github.com/ayagaidi/libya-dev-api/actions/workflows/tests.yml)
[![License](https://img.shields.io/github/license/ayagaidi/libya-dev-api)](LICENSE)

**Laravel 13 · REST · OpenAPI 3.1 · JSON · Cache · Rate limiting · Tests · CI**

Libya Dev API is API-first: Laravel powers the backend, but consumers do **not** need PHP or Laravel. Any language that can make an HTTP request can integrate it.

## Current scope

- Libyan municipalities and cities through the pinned `Libya Locations v1.2.0` dataset
- search by Arabic name, English name or stable slug
- Libyan mobile phone normalization (`091...`, `+21891...`, `0021891...`)
- mobile format and known-prefix validation
- telecom operator prefix metadata with explicit source provenance and verification strength
- **26-bank commercial bank directory from the Central Bank of Libya**
- bank search by Arabic/English name, city or stable slug
- **Libya public-holiday calendar** based on Law No. 5 of 2012
- confirmed 2026 religious holiday dates with annual decision/source metadata
- future religious dates intentionally returned as `null` until officially confirmed
- OpenAPI 3.1 contract at `/openapi.json`
- interactive Swagger UI at `/docs`
- public CORS, API rate limiting and cache-backed source retrieval
- PHPUnit feature tests + Laravel Pint in GitHub Actions

## Example

```bash
curl -X POST http://localhost:8000/api/v1/phone/validate \
  -H 'Content-Type: application/json' \
  -d '{"phone":"0912345678"}'
```

Example response:

```json
{
  "data": {
    "input": "0912345678",
    "normalized": {
      "national": "0912345678",
      "e164": "+218912345678",
      "digits": "218912345678"
    },
    "valid": true,
    "is_structurally_valid_mobile": true,
    "is_supported_mobile_range": true,
    "type": "mobile",
    "prefix": "091",
    "operator": {
      "slug": "almadar",
      "name_en": "Almadar Aljadid",
      "name_ar": "المدار الجديد"
    }
  }
}
```

> Operator detection is prefix/range metadata. It is **not** a live subscriber or mobile-number-portability lookup.

## API endpoints

| Method | Endpoint | Purpose |
|---|---|---|
| GET | `/api/v1/meta` | API + Libya metadata |
| GET | `/api/v1/locations/municipalities` | municipalities, optional `?q=` |
| GET | `/api/v1/locations/municipalities/{slug}` | municipality by stable slug |
| GET | `/api/v1/locations/cities` | cities/places, optional `?q=` |
| POST | `/api/v1/phone/normalize` | normalize a Libyan mobile number |
| POST | `/api/v1/phone/validate` | validate format + known operator range |
| GET | `/api/v1/telecom/operators` | operator prefixes + provenance |
| GET | `/api/v1/banks` | commercial banks, optional `?q=` / `?city=` |
| GET | `/api/v1/banks/{slug}` | bank by stable slug |
| GET | `/api/v1/holidays` | current-year holiday calendar in `Africa/Tripoli` |
| GET | `/api/v1/holidays/{year}` | holiday calendar for a specific year |
| GET | `/openapi.json` | OpenAPI contract |
| GET | `/docs` | interactive API documentation |

## Banks example

```bash
curl 'http://localhost:8000/api/v1/banks?q=النوران'
```

Bank records expose a stable slug, Arabic/English name, city, website and source metadata. The directory is sourced from the Central Bank of Libya rather than crowdsourced guesses.

## Holidays example

```bash
curl 'http://localhost:8000/api/v1/holidays/2026'
```

The holiday module distinguishes between:

- `confirmed_official_decision` — an annual date has a sourced official decision/announcement
- `statutory_fixed_date` — the Gregorian date is fixed by the official-holidays law
- `requires_annual_confirmation` — a religious date is intentionally not guessed for that year

The API does not infer substitute days, bridge leave or administrative extensions unless an explicit source is recorded.

## Language-agnostic integration

JavaScript:

```js
const response = await fetch('https://your-host.ly/api/v1/phone/validate', {
  method: 'POST',
  headers: {'Content-Type': 'application/json'},
  body: JSON.stringify({phone: '0912345678'})
});

console.log(await response.json());
```

Python:

```python
import requests

result = requests.post(
    'https://your-host.ly/api/v1/phone/validate',
    json={'phone': '0912345678'},
).json()
```

Flutter / Dart:

```dart
final response = await http.post(
  Uri.parse('https://your-host.ly/api/v1/phone/validate'),
  headers: {'Content-Type': 'application/json'},
  body: jsonEncode({'phone': '0912345678'}),
);
```

## Install

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan serve
```

Then open:

```text
http://localhost:8000/docs
```

## Data provenance

Locations are read from the version-pinned open dataset `ayagaidi/libyancityseeds` (`v1.2.0`). Telecom prefix metadata carries source URLs and verification strength. Bank records are based on the Central Bank of Libya commercial-bank directory. Holiday definitions come from Law No. 5 of 2012, while floating religious dates are only populated for a year when a source is available.

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
