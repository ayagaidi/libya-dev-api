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

## Important distinction

Prefix metadata describes allocated/published number ranges. The API does not perform live subscriber, SIM ownership, routing or portability lookups.
