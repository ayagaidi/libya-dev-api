<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ExchangeRateApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_exchange_rates_are_parsed_and_units_are_normalized(): void
    {
        Http::fake([
            config('libya_exchange.source_url') => Http::response($this->sampleHtml(), 200),
        ]);

        $this->getJson('/api/v1/exchange-rates')
            ->assertOk()
            ->assertJsonPath('data.0.code', 'USD')
            ->assertJsonPath('data.0.rates.average', 6.3564)
            ->assertJsonPath('data.1.code', 'DZD')
            ->assertJsonPath('data.1.unit.multiplier', 10)
            ->assertJsonPath('data.1.rates.per_unit_average', 0.0477)
            ->assertJsonPath('meta.source_date', '2026-09-07')
            ->assertJsonPath('meta.stale', false);
    }

    public function test_converter_uses_per_unit_rate_and_supports_lyd(): void
    {
        Http::fake([
            config('libya_exchange.source_url') => Http::response($this->sampleHtml(), 200),
        ]);

        $this->postJson('/api/v1/currency/convert', [
            'amount' => 10,
            'from' => 'DZD',
            'to' => 'LYD',
        ])->assertOk()
            ->assertJsonPath('data.result', 0.477)
            ->assertJsonPath('data.rate_type', 'average');
    }

    public function test_exchange_rate_source_failure_returns_503_without_cached_data(): void
    {
        Http::fake([
            config('libya_exchange.source_url') => Http::response('unavailable', 500),
        ]);

        $this->getJson('/api/v1/exchange-rates')
            ->assertStatus(503)
            ->assertJsonPath('error', 'source_unavailable');
    }

    private function sampleHtml(): string
    {
        return <<<'HTML'
<table>
<tr><th>Date</th><th>Currency</th><th>Unit</th><th>Average</th><th>Sell</th><th>Buy</th></tr>
<tr><td>Date: 2026-09-07</td><td>Currency: American Dollar</td><td>Unit: One Dollar</td><td>6.3564 LYD</td><td>6.3723 LYD</td><td>6.3405 LYD</td></tr>
<tr><td>Date: 2026-09-07</td><td>Currency: Algerian Dinar</td><td>Unit: 10 Dinar</td><td>0.477 LYD</td><td>0.478 LYD</td><td>0.475 LYD</td></tr>
</table>
HTML;
    }
}
