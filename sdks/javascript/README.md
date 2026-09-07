# libya-dev-api-client

JavaScript client for [Libya Dev API](https://github.com/ayagaidi/libya-dev-api).

```js
import { LibyaDevApi } from 'libya-dev-api-client';

const api = new LibyaDevApi({ baseUrl: 'https://YOUR-API-DOMAIN/api/v1' });
const nearby = await api.nearbyMunicipalities(32.8872, 13.1913, 50, 5);
console.log(nearby.data);
```

The client covers locations, Geo helpers, phone utilities, telecom metadata, banks, holidays, official CBL exchange rates, currency conversion and business-calendar endpoints.

## Source install before registry publication

```bash
npm install github:ayagaidi/libya-dev-api#v0.4.0
```

For the monorepo source package, see `sdks/javascript` in the repository.

MIT License.
