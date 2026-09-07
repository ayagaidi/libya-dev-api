# Data Sources

Libya Dev API keeps Libya-specific facts traceable to a source and records verification strength where appropriate.

## Locations

The API consumes the **pinned** `Libya Locations v1.2.0` release rather than the moving `master` branch.

- Repository: https://github.com/ayagaidi/libyancityseeds
- Municipalities JSON: https://raw.githubusercontent.com/ayagaidi/libyancityseeds/v1.2.0/data/municipalities.json
- Cities JSON: https://raw.githubusercontent.com/ayagaidi/libyancityseeds/v1.2.0/data/cities.json

The dataset contains 141 municipality records and 50 city/place records in v1.2.0.

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

`095` is intentionally labelled with weaker verification than the current direct 091–094 sources.

## Commercial banks

Verification: `current_primary_source`

The bank directory is based on the Central Bank of Libya commercial-bank directory:
- https://cbl.gov.ly/banks/

Source checked: `2026-09-07`.

The API currently exposes the 26 entries shown by that directory with stable slugs, Arabic/English names, head-office city and website. It does not invent SWIFT/BIC, IBAN, branch, routing or licensing details not present in the maintained source.

## Official holidays

Primary legal basis: **Law No. 5 of 2012 on Official Holidays**.
- https://ssf.gov.ly/wp-content/uploads/2017/01/%D8%A7%D9%84%D8%B9%D8%AF%D8%AF-1-%D8%A7%D9%84%D8%B3%D9%86%D8%A9-%D8%A7%D9%84%D8%A3%D9%88%D9%84%D9%89-13-%D8%B1%D8%A8%D9%8A%D8%B9-%D8%A7%D9%84%D8%A3%D8%AE%D8%B1-1433%D9%87%D9%80-%D8%A7%D9%84%D9%85%D9%88%D8%A7%D9%81%D9%82-201236-%D9%85.pdf

2026 annual confirmations include:
- 17 February: https://lana.gov.ly/post.php?id=351138&lang=ar
- Eid al-Fitr: https://lana.gov.ly/post.php?id=353006&lang=ar
- Labour Day: https://lana.gov.ly/post.php?id=356111&lang=ar
- Arafah + Eid al-Adha: https://lana.gov.ly/post.php?id=358004&lang=ar
- Hijri New Year: https://lana.gov.ly/post.php?id=359503&lang=ar
- Prophet's Birthday: https://lana.gov.ly/post.php?id=364180&lang=ar

For a year without confirmed religious dates, the API returns those dates as `null` with status `requires_annual_confirmation`. Substitute holidays, bridge leave and administrative extensions are not inferred without an explicit source.

## Official exchange rates

Verification: `live_primary_source`

Current buy, sell and average exchange rates are fetched from the official Central Bank of Libya exchange-rate page:
- https://cbl.gov.ly/en/currency-exchange-rates/

The source publishes a date, currency, quoted unit, average, sell and buy values. Some rows quote multiple foreign-currency units (for example 10 or 100 units). The API preserves the published quote and also exposes normalized `per_unit_average`, `per_unit_sell` and `per_unit_buy` values.

Successful source responses are cached. A limited `last-good` cache can be returned with `meta.stale=true` if the CBL page is temporarily unavailable. If there is no current or last-good data, the API returns `503 source_unavailable` instead of inventing a rate.

The converter is a mathematical reference utility over the selected published CBL column. It does not add fees, determine transaction eligibility, or represent a guaranteed executable market quote.

## Public-sector business week

Verification: `official_decision_reference`

The general public-sector workweek is modeled from **Council of Ministers Decision No. 356 of 2012**, amending Decision No. 10 of 2012: Sunday through Thursday are scheduled workdays and Friday/Saturday are weekly rest days.
- https://lana.gov.ly/post.php?id=149105&lang=ar

A current official government service page also publishes Sunday–Thursday working hours and Friday/Saturday as official weekly rest:
- https://customs.gov.ly/services/

The business-day API combines this weekly schedule with the official-holiday module. It is explicitly a general public-sector calendar: essential services, private employers, banks, payment/settlement systems and special administrative decisions may operate on different schedules.

For future years whose religious holidays have not all been officially confirmed, responses are marked `provisional_incomplete_holiday_calendar`.

## Important distinctions

Prefix metadata is not a live subscriber or portability lookup. Bank data is directory metadata, not financial advice or live operational status. Holiday data distinguishes fixed law-based dates from annual confirmations. Exchange rates are source-backed reference data, and business-day output models the general public-sector schedule rather than every sector's operating calendar.
