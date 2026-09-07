# Libya Dev API SDKs

v0.5.0 prepares the JavaScript, Python and Dart/Flutter clients for official registry distribution. The three clients remain independently tested against the same public API contract.

## Registry targets

| SDK | Package | Registry | Release tag |
|---|---|---|---|
| JavaScript | `libya-dev-api-client` | npm | `js-v0.5.0` |
| Python | `libya-dev-api-client` | PyPI | `python-v0.5.0` |
| Dart / Flutter | `libya_dev_api` | pub.dev | `dart-v0.5.0` |

Until a registry package is linked from the main README, use the source-install fallback below rather than assuming publication has completed.

## JavaScript

After cloning the repository:

```bash
npm install ./sdks/javascript
```

```js
import { LibyaDevApi } from 'libya-dev-api-client';

const api = new LibyaDevApi({baseUrl: 'https://your-host.ly/api/v1'});
const nearest = await api.nearestMunicipalities(32.8872, 13.1913);
```

## Python

From the v0.5.0 tag after release:

```bash
pip install "libya-dev-api-client @ git+https://github.com/ayagaidi/libya-dev-api.git@v0.5.0#subdirectory=sdks/python"
```

```python
from libya_dev_api import LibyaDevApi

api = LibyaDevApi('https://your-host.ly/api/v1')
print(api.nearest_municipalities(32.8872, 13.1913))
```

## Dart / Flutter

```yaml
dependencies:
  libya_dev_api:
    git:
      url: https://github.com/ayagaidi/libya-dev-api.git
      ref: v0.5.0
      path: sdks/dart
```

Each SDK covers locations, Geo, phone, telecom, banks, holidays, exchange rates, currency conversion and business-calendar endpoints.

See [`../docs/PUBLISHING.md`](../docs/PUBLISHING.md) for maintainer publishing setup.
