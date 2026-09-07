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

- Libya General Authority for Communications and Informatics — Tawasul supported numbers: https://tawasul.cim.gov.ly/HowToUse
- Almadar number-selection service: https://wazi.almadar.ly/

### Libyana — 092 / 094

Verification: `current_primary_source`

- Libyana Call Me service: https://libyana.ly/call-me/
- Libyana SIM / special ranges: https://libyana.ly/sim-card/

### LibyaPhone / LTT — 095

Verification: `historical_numbering_plan_current_operator_reference`

- ITU Libya numbering-plan bulletin (2006): https://www.itu.int/dms_pub/itu-t/opb/sp/T-SP-OB.854-2006-OAS-PDF-E.pdf
- Current Libya communications authority company/operator references: https://www.cim.gov.ly/numbering_companies.html

`095` is intentionally labelled with weaker verification than the current direct 091–094 sources. Contributions that provide a stronger current primary source are welcome.

## Commercial banks

Verification: `current_primary_source`

The bank directory is based on the Central Bank of Libya's current commercial-bank directory:

- https://cbl.gov.ly/banks/

Source checked: `2026-09-07`.

The API currently exposes the 26 commercial-bank entries shown by the Central Bank directory, with stable slugs, Arabic/English names, head-office city and website. The API does not infer licensing status beyond the bank's presence in that directory and does not invent SWIFT/BIC, IBAN, branch or routing data.

## Official holidays

### Legal definitions

Primary legal basis: **Law No. 5 of 2012 on Official Holidays** (`القانون رقم 5 لسنة 2012 بشأن العطلات الرسمية`). The attached schedule defines the religious holidays and the fixed Gregorian holidays, including 17 February, 1 May, 16 September, 23 October and 24 December.

Official-government-hosted Gazette PDF:

- https://ssf.gov.ly/wp-content/uploads/2017/01/%D8%A7%D9%84%D8%B9%D8%AF%D8%AF-1-%D8%A7%D9%84%D8%B3%D9%86%D8%A9-%D8%A7%D9%84%D8%A3%D9%88%D9%84%D9%89-13-%D8%B1%D8%A8%D9%8A%D8%B9-%D8%A7%D9%84%D8%A3%D8%AE%D8%B1-1433%D9%87%D9%80-%D8%A7%D9%84%D9%85%D9%88%D8%A7%D9%81%D9%82-201236-%D9%85.pdf

### 2026 annual confirmations

Year-specific religious dates are stored only when there is a source for that year's announcement/decision.

- 17 February: https://lana.gov.ly/post.php?id=351138&lang=ar
- Eid al-Fitr decision: https://lana.gov.ly/post.php?id=352928&lang=ar
- Eid al-Fitr first day confirmation: https://lana.gov.ly/post.php?id=353006&lang=ar
- Labour Day: https://lana.gov.ly/post.php?id=356111&lang=ar
- Arafah + Eid al-Adha: https://lana.gov.ly/post.php?id=358004&lang=ar
- Hijri New Year: https://lana.gov.ly/post.php?id=359503&lang=ar
- Prophet's Birthday: https://lana.gov.ly/post.php?id=364180&lang=ar

For a year without confirmed religious dates, the API returns those dates as `null` with status `requires_annual_confirmation` rather than calculating or guessing them. Fixed statutory dates remain available as `statutory_fixed_date`.

The API also does **not** infer substitute holidays, bridge leave, or additional administrative leave when a holiday falls near a weekend. Those are exposed only when an explicit source is added.

## Important distinction

Prefix metadata describes allocated/published number ranges. The API does not perform live subscriber, SIM ownership, routing or portability lookups. Bank data is directory metadata, not financial advice or live bank-operational status. Holiday data distinguishes statutory dates from year-specific confirmations.
