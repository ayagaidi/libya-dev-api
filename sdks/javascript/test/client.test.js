import assert from 'node:assert/strict';
import test from 'node:test';
import { LibyaDevApi, LibyaDevApiError } from '../src/index.js';

const jsonResponse = (payload, status = 200) => new Response(JSON.stringify(payload), {
  status,
  headers: { 'Content-Type': 'application/json' },
});

test('nearestMunicipalities builds the expected query', async () => {
  let seenUrl;
  const api = new LibyaDevApi({
    baseUrl: 'https://api.example.ly/api/v1/',
    fetchImpl: async (url) => {
      seenUrl = url;
      return jsonResponse({ data: [{ slug: 'tripoli' }] });
    },
  });

  const result = await api.nearestMunicipalities(32.8872, 13.1913, 3);
  assert.equal(result.data[0].slug, 'tripoli');
  assert.equal(seenUrl.searchParams.get('lat'), '32.8872');
  assert.equal(seenUrl.searchParams.get('limit'), '3');
});

test('convertCurrency sends JSON and surfaces API errors', async () => {
  const api = new LibyaDevApi({
    baseUrl: 'https://api.example.ly/api/v1',
    fetchImpl: async (_url, options) => {
      const body = JSON.parse(options.body);
      assert.equal(body.rate_type, 'sell');
      return jsonResponse({ message: 'unsupported_currency' }, 422);
    },
  });

  await assert.rejects(
    () => api.convertCurrency({ amount: 10, from: 'AAA', to: 'LYD', rateType: 'sell' }),
    (error) => error instanceof LibyaDevApiError && error.status === 422,
  );
});
