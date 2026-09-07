# libya-dev-api-client

Python client for [Libya Dev API](https://github.com/ayagaidi/libya-dev-api).

```python
from libya_dev_api import LibyaDevApi

api = LibyaDevApi("https://YOUR-API-DOMAIN/api/v1")
nearby = api.nearby_municipalities(32.8872, 13.1913, radius_km=50, limit=5)
print(nearby["data"])
```

The client uses the Python standard library for HTTP and covers locations, Geo helpers, phone utilities, telecom metadata, banks, holidays, official CBL exchange rates, currency conversion and business-calendar endpoints.

MIT License.
