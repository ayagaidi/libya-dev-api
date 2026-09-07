# Libya Dev API 🇱🇾

> **The open developer API for Libya.** One stable HTTP/JSON interface for Libya-specific locations, phone normalization, telecom metadata and future developer primitives.

[![Laravel Quality](https://github.com/ayagaidi/libya-dev-api/actions/workflows/tests.yml/badge.svg)](https://github.com/ayagaidi/libya-dev-api/actions/workflows/tests.yml)

**Laravel 13 · REST · OpenAPI 3.1 · JSON · Cache · Rate limiting · Tests · CI**

Libya Dev API is API-first: Laravel powers the backend, but consumers do **not** need PHP or Laravel. Any language that can make an HTTP request can integrate it.

## v0.1 scope

- Libyan municipalities and cities through the pinned `Libya Locations v1.2.0` dataset
- search by Arabic name, English name or stable slug
- Libyan mobile phone normalization (`091...`, `+21891...`, `0021891...`)
- mobile format and known-prefix validation
- telecom operator prefix metadata with explicit source provenance and verification strength
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
| GET | `/openapi.json` | OpenAPI contract |
| GET | `/docs` | interactive API documentation |

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

Locations are read from the version-pinned open dataset:

- `ayagaidi/libyancityseeds` — `v1.2.0`
- 141 municipalities
- Arabic/English names and stable slugs

Telecom prefix metadata records source URLs and verification strength in the API response. High-confidence current ranges in v0.1 are `091/093` (Almadar) and `092/094` (Libyana). `095` is retained with an explicitly weaker historical/current-operator-reference status rather than presented as equally current evidence.

See [`DATA_SOURCES.md`](DATA_SOURCES.md).

## Design principles

1. **No guessed Libya-specific facts.** Missing or uncertain data is labelled, not invented.
2. **Stable versioned API.** Breaking changes require a new API version.
3. **Language agnostic.** HTTP + JSON + OpenAPI are the contract.
4. **Source provenance.** Country-specific metadata should point to its source.
5. **Small core, useful modules.** Add banks, holidays, postal/address helpers and other modules only with maintainable sources.

## Roadmap

See [`ROADMAP.md`](ROADMAP.md).

## Security

See [`SECURITY.md`](SECURITY.md). Do not report suspected vulnerabilities in a public issue.

## License

MIT. See [`LICENSE`](LICENSE).

Maintained by **Aya Aljaidi** — building open developer infrastructure for Libya. 🇱🇾
