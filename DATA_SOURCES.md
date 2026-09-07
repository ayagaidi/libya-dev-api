# Data Sources

Libya Dev API keeps Libya-specific facts traceable to a source and records verification strength where appropriate.

## Locations + Geo

The API consumes the **pinned** `Libya Locations v1.2.0` release rather than the moving default branch.

- Repository: https://github.com/ayagaidi/libyancityseeds
- Municipalities JSON: https://raw.githubusercontent.com/ayagaidi/libyancityseeds/v1.2.0/data/municipalities.json
- Cities JSON: https://raw.githubusercontent.com/ayagaidi/libyancityseeds/v1.2.0/data/cities.json
- Municipality points JSON: https://raw.githubusercontent.com/ayagaidi/libyancityseeds/v1.2.0/data/municipality-points.json
- Municipality points GeoJSON: https://raw.githubusercontent.com/ayagaidi/libyancityseeds/v1.2.0/data/municipality-points.geojson

The v1.2.0 point dataset records coordinate provenance and deliberately leaves municipalities without sufficiently verified coordinates as `null`. Geo nearest/nearby helpers consider mapped points only and calculate straight-line Haversine distance; they do not imply road distance or municipality boundary containment.

## Telecom prefixes

### Almadar — 091 / 093
Verification: `current_primary_source`
- https://tawasul.cim.gov.ly/HowToUse
- https://wazi.almadar.ly/

### Libyana — 092 / 094
Verification: `current_primary_source`
- https://libyana.ly/call-me/
- https://libyana.ly/sim-card/

### LibyaPhone / LTT — 095
Verification: `historical_numbering_plan_current_operator_reference`
- https://www.itu.int/dms_pub/itu-t/opb/sp/T-SP-OB.854-2006-OAS-PDF-E.pdf
- https://www.cim.gov.ly/numbering_companies.html

## Commercial banks

Verification: `current_primary_source`
- https://cbl.gov.ly/banks/

Source checked: `2026-09-07`. The API exposes directory metadata only and does not invent SWIFT/BIC, IBAN, routing or branch data.

## Official holidays

Primary legal basis: Law No. 5 of 2012 on Official Holidays.
- https://ssf.gov.ly/wp-content/uploads/2017/01/%D8%A7%D9%84%D8%B9%D8%AF%D8%AF-1-%D8%A7%D9%84%D8%B3%D9%86%D8%A9-%D8%A7%D9%84%D8%A3%D9%88%D9%84%D9%89-13-%D8%B1%D8%A8%D9%8A%D8%B9-%D8%A7%D9%84%D8%A3%D8%AE%D8%B1-1433%D9%87%D9%80-%D8%A7%D9%84%D9%85%D9%88%D8%A7%D9%81%D9%82-201236-%D9%85.pdf

For a year without confirmed religious dates, those dates remain `null` with `requires_annual_confirmation` rather than being guessed.

## Official exchange rates

Verification: `live_primary_source`
- https://cbl.gov.ly/en/currency-exchange-rates/

The API preserves published quote units and exposes normalized per-unit buy/sell/average values. Successful source responses are cached; stale fallback is explicitly marked.

## Public-sector business week

Verification: `official_decision_reference`
- https://lana.gov.ly/post.php?id=149105&lang=ar
- https://customs.gov.ly/services/

The general public-sector model uses Sunday–Thursday as scheduled workdays and Friday/Saturday as weekly rest, then excludes known official holidays. Special sectors may differ.

## Important distinctions

Geo distance is straight-line reference distance, not routing or legal boundary containment. Prefix metadata is not a live subscriber lookup. Bank data is directory metadata. Holiday data separates fixed dates from annual confirmation. Exchange rates are source-backed reference data. Business-day output models the general public-sector schedule.
