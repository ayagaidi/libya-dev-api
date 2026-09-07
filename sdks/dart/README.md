# libya_dev_api

Dart and Flutter client for [Libya Dev API](https://github.com/ayagaidi/libya-dev-api).

```dart
import 'package:libya_dev_api/libya_dev_api.dart';

final api = LibyaDevApi(baseUrl: 'https://YOUR-API-DOMAIN/api/v1');
final nearby = await api.nearbyMunicipalities(32.8872, 13.1913, radiusKm: 50, limit: 5);
print(nearby['data']);
api.close();
```

The client covers locations, Geo helpers, phone utilities, telecom metadata, banks, holidays, official CBL exchange rates, currency conversion and business-calendar endpoints.

MIT License.
