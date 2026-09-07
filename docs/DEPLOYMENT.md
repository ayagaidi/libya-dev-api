# Hosted API deployment

Libya Dev API ships with a Dockerfile and Railway config-as-code so the public API can be deployed without changing application code.

## Railway

1. Connect `ayagaidi/libya-dev-api` as a Railway service.
2. Railway detects the root `Dockerfile` automatically.
3. Set production variables:

```text
APP_NAME=Libya Dev API
APP_ENV=production
APP_DEBUG=false
APP_URL=https://YOUR-DOMAIN
APP_KEY=base64:...
LOG_CHANNEL=stderr
LOG_LEVEL=info
CACHE_STORE=file
LIBYA_LOCATIONS_VERSION=1.2.0
LIBYA_LOCATIONS_CACHE_TTL=86400
LIBYA_API_RATE_LIMIT=60
```

Generate `APP_KEY` locally with:

```bash
php artisan key:generate --show
```

`railway.json` configures `/health` as the deployment healthcheck and an on-failure restart policy. The container listens on Railway's injected `PORT`.

After deploy, verify:

```text
GET https://YOUR-DOMAIN/health
GET https://YOUR-DOMAIN/api/v1/meta
GET https://YOUR-DOMAIN/openapi.json
GET https://YOUR-DOMAIN/docs
```

## Production notes

- Keep `APP_DEBUG=false`.
- Use HTTPS only for public traffic.
- Keep the existing API rate limit enabled.
- The exchange-rate module depends on the upstream Central Bank of Libya page and has a last-good cache fallback.
- The location/Geo modules depend on the pinned `Libya Locations v1.2.0` raw files.
- `/health` checks application readiness only; it intentionally does not fail deployment because an external upstream source is temporarily unavailable.
