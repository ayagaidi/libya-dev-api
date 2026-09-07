# Libya Dev API 🇱🇾

**واجهة API مفتوحة للمطورين في ليبيا** تجمع بيانات وأدوات ليبية قابلة للاستخدام من أي لغة عبر HTTP/JSON.

## النطاق الحالي

- البلديات والمدن من `Libya Locations v1.2.0`
- **Municipality Points + GeoJSON** من نفس الإصدار المثبت
- **Nearest / Nearby** لحساب أقرب البلديات ذات الإحداثيات باستخدام Haversine
- البلدية التي لا يوجد لها coordinate موثوق تبقى `null` ولا يتم تخمينها
- توحيد والتحقق من أرقام الهاتف الليبية
- بيانات مشغلي الاتصالات ومصادرها
- دليل 26 مصرفًا تجاريًا من مصرف ليبيا المركزي
- تقويم العطلات الرسمية
- أسعار الصرف الرسمية من مصرف ليبيا المركزي + Currency Converter
- Business Day helpers
- **SDKs جاهزة كمصدر لـJavaScript وPython وDart/Flutter**
- OpenAPI + Swagger + Tests + GitHub Actions

### Geo API

```text
GET /api/v1/geo/municipality-points
GET /api/v1/geo/municipalities.geojson
GET /api/v1/geo/nearest?lat=32.8872&lng=13.1913
GET /api/v1/geo/nearby?lat=32.8872&lng=13.1913&radius_km=50
```

### SDK مثال

JavaScript:

```js
const api = new LibyaDevApi({baseUrl: 'https://your-host.ly/api/v1'});
const result = await api.nearestMunicipalities(32.8872, 13.1913);
```

Python:

```python
api = LibyaDevApi('https://your-host.ly/api/v1')
result = api.nearest_municipalities(32.8872, 13.1913)
```

Flutter / Dart:

```dart
final api = LibyaDevApi(baseUrl: 'https://your-host.ly/api/v1');
final result = await api.nearestMunicipalities(32.8872, 13.1913);
```

تفاصيل التثبيت من GitHub موجودة في [`sdks/README.md`](sdks/README.md). نشر الحزم على npm/PyPI/pub.dev خطوة منفصلة، لذلك لا ندعي وجود package منشورة قبل ربطها رسميًا في README.

Laravel هو الـbackend فقط؛ استهلاك الـAPI لا يحتاج PHP أو Laravel.
