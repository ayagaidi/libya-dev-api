# Libya Dev API SDKs

v0.4.0 ships source SDKs for JavaScript, Python and Dart/Flutter. They are tested in GitHub Actions against the same public API contract.

> Registry publication to npm, PyPI and pub.dev is intentionally separate from this release. Do not assume a registry package exists until a published package is linked from the main README.

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

For browser ESM, a tagged GitHub release can also be consumed through a GitHub CDN such as jsDelivr.

## Python

From a tagged release:

```bash
pip install "libya-dev-api-client @ git+https://github.com/ayagaidi/libya-dev-api.git@v0.4.0#subdirectory=sdks/python"
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
      ref: v0.4.0
      path: sdks/dart
```

```dart
final api = LibyaDevApi(baseUrl: 'https://your-host.ly/api/v1');
final nearest = await api.nearestMunicipalities(32.8872, 13.1913);
```

Each SDK includes wrappers for locations, Geo, phone, telecom, banks, holidays, exchange rates, currency conversion and business-calendar endpoints.
