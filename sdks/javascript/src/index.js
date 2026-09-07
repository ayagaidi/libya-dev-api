export class LibyaDevApiError extends Error {
  constructor(message, status, payload) {
    super(message);
    this.name = 'LibyaDevApiError';
    this.status = status;
    this.payload = payload;
  }
}

export class LibyaDevApi {
  constructor({ baseUrl, fetchImpl = globalThis.fetch } = {}) {
    if (!baseUrl) throw new TypeError('baseUrl is required');
    if (!fetchImpl) throw new TypeError('fetch implementation is required');

    this.baseUrl = baseUrl.replace(/\/$/, '');
    this.fetchImpl = fetchImpl;
  }

  async request(path, { method = 'GET', query = {}, body } = {}) {
    const url = new URL(`${this.baseUrl}${path}`);

    for (const [key, value] of Object.entries(query)) {
      if (value !== undefined && value !== null && value !== '') {
        url.searchParams.set(key, String(value));
      }
    }

    const response = await this.fetchImpl(url, {
      method,
      headers: body ? { 'Content-Type': 'application/json' } : undefined,
      body: body ? JSON.stringify(body) : undefined,
    });

    const payload = await response.json();

    if (!response.ok) {
      throw new LibyaDevApiError(payload.message ?? 'Libya Dev API request failed', response.status, payload);
    }

    return payload;
  }

  meta() { return this.request('/meta'); }
  municipalities(q) { return this.request('/locations/municipalities', { query: { q } }); }
  municipality(slug) { return this.request(`/locations/municipalities/${encodeURIComponent(slug)}`); }
  cities(q) { return this.request('/locations/cities', { query: { q } }); }
  municipalityPoints(mappedOnly = false) { return this.request('/geo/municipality-points', { query: { mapped_only: mappedOnly ? 1 : undefined } }); }
  nearestMunicipalities(lat, lng, limit = 5) { return this.request('/geo/nearest', { query: { lat, lng, limit } }); }
  nearbyMunicipalities(lat, lng, radiusKm = 50, limit = 20) { return this.request('/geo/nearby', { query: { lat, lng, radius_km: radiusKm, limit } }); }
  normalizePhone(phone) { return this.request('/phone/normalize', { method: 'POST', body: { phone } }); }
  validatePhone(phone) { return this.request('/phone/validate', { method: 'POST', body: { phone } }); }
  telecomOperators() { return this.request('/telecom/operators'); }
  banks({ q, city } = {}) { return this.request('/banks', { query: { q, city } }); }
  bank(slug) { return this.request(`/banks/${encodeURIComponent(slug)}`); }
  holidays(year) { return this.request(year ? `/holidays/${year}` : '/holidays'); }
  exchangeRates() { return this.request('/exchange-rates'); }
  exchangeRate(currency) { return this.request(`/exchange-rates/${encodeURIComponent(currency.toUpperCase())}`); }
  convertCurrency({ amount, from, to, rateType = 'average' }) { return this.request('/currency/convert', { method: 'POST', body: { amount, from, to, rate_type: rateType } }); }
  isBusinessDay(date) { return this.request('/calendar/is-business-day', { query: { date } }); }
  nextBusinessDay(date) { return this.request('/calendar/next-business-day', { query: { date } }); }
  businessDays(from, to) { return this.request('/calendar/business-days', { query: { from, to } }); }
}
