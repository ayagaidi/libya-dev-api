<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class ExchangeRateService
{
    public function current(): array
    {
        $cacheKey = 'cbl:exchange-rates:current';
        $lastGoodKey = 'cbl:exchange-rates:last-good';

        if ($cached = Cache::get($cacheKey)) {
            return $cached;
        }

        try {
            $payload = $this->fetchFresh();
            Cache::put($cacheKey, $payload, (int) config('libya_exchange.cache_ttl_seconds', 1800));
            Cache::put($lastGoodKey, $payload, (int) config('libya_exchange.stale_ttl_seconds', 604800));

            return $payload;
        } catch (RuntimeException $exception) {
            if ($stale = Cache::get($lastGoodKey)) {
                $stale['meta']['stale'] = true;
                $stale['meta']['stale_reason'] = $exception->getMessage();

                return $stale;
            }

            throw $exception;
        }
    }

    public function find(string $currency): ?array
    {
        $code = strtoupper($currency);

        if ($code === 'LYD') {
            return $this->lydRate();
        }

        foreach ($this->current()['data'] as $rate) {
            if ($rate['code'] === $code) {
                return $rate;
            }
        }

        return null;
    }

    public function convert(float $amount, string $from, string $to, string $rateType = 'average'): array
    {
        $from = strtoupper($from);
        $to = strtoupper($to);
        $fromRate = $this->find($from);
        $toRate = $this->find($to);

        if (! $fromRate || ! $toRate) {
            throw new RuntimeException('unsupported_currency');
        }

        $fromPerUnit = $fromRate['rates']['per_unit_'.$rateType];
        $toPerUnit = $toRate['rates']['per_unit_'.$rateType];
        $amountLyd = $amount * $fromPerUnit;
        $result = $amountLyd / $toPerUnit;

        return [
            'data' => [
                'amount' => $amount,
                'from' => $from,
                'to' => $to,
                'rate_type' => $rateType,
                'result' => round($result, 8),
                'intermediate_lyd' => round($amountLyd, 8),
            ],
            'meta' => [
                'source' => config('libya_exchange.source_name'),
                'source_url' => config('libya_exchange.source_url'),
                'source_date' => $this->current()['meta']['source_date'] ?? null,
                'note' => 'Mathematical conversion using the selected published CBL column. It does not include fees, spreads outside the published column, or transaction eligibility rules.',
            ],
        ];
    }

    private function fetchFresh(): array
    {
        $sourceUrl = (string) config('libya_exchange.source_url');
        $response = Http::accept('text/html')->timeout(10)->retry(2, 250)->get($sourceUrl);

        if (! $response->successful()) {
            throw new RuntimeException('Central Bank of Libya exchange-rate source is unavailable.');
        }

        $rates = $this->parseHtml($response->body());

        if ($rates === []) {
            throw new RuntimeException('Central Bank of Libya exchange-rate source returned no parseable rates.');
        }

        $sourceDate = collect($rates)->pluck('date')->filter()->sortDesc()->first();

        return [
            'data' => $rates,
            'meta' => [
                'base_currency' => 'LYD',
                'source' => config('libya_exchange.source_name'),
                'source_url' => $sourceUrl,
                'source_date' => $sourceDate,
                'fetched_at' => now('Africa/Tripoli')->toIso8601String(),
                'stale' => false,
                'note' => 'Official CBL rows may quote 10 or 100 foreign-currency units. per_unit_* values normalize every quote to one foreign-currency unit.',
            ],
        ];
    }

    private function parseHtml(string $html): array
    {
        preg_match_all('/<tr\b[^>]*>(.*?)<\/tr>/is', $html, $rows);
        $catalog = config('libya_exchange.currencies', []);
        $rates = [];

        foreach ($rows[1] ?? [] as $row) {
            preg_match_all('/<t[dh]\b[^>]*>(.*?)<\/t[dh]>/is', $row, $cells);
            $cells = array_map(fn (string $cell): string => $this->cleanCell($cell), $cells[1] ?? []);

            if (count($cells) < 6) {
                continue;
            }

            preg_match('/\d{4}-\d{2}-\d{2}/', $cells[0], $dateMatch);
            $sourceName = trim((string) preg_replace('/^Currency:\s*/i', '', $cells[1]));
            $code = $this->codeForSourceName($sourceName, $catalog);

            if (! $code) {
                continue;
            }

            $unitLabel = trim((string) preg_replace('/^Unit:\s*/i', '', $cells[2]));
            $multiplier = $this->unitMultiplier($unitLabel, (int) ($catalog[$code]['unit_multiplier'] ?? 1));
            $average = $this->number($cells[3]);
            $sell = $this->number($cells[4]);
            $buy = $this->number($cells[5]);

            if ($average === null || $sell === null || $buy === null) {
                continue;
            }

            $rates[] = [
                'code' => $code,
                'name_en' => $catalog[$code]['name_en'],
                'source_name' => $sourceName,
                'date' => $dateMatch[0] ?? null,
                'unit' => ['label' => $unitLabel, 'multiplier' => $multiplier],
                'rates' => [
                    'average' => $average,
                    'sell' => $sell,
                    'buy' => $buy,
                    'per_unit_average' => round($average / $multiplier, 8),
                    'per_unit_sell' => round($sell / $multiplier, 8),
                    'per_unit_buy' => round($buy / $multiplier, 8),
                ],
            ];
        }

        return $rates;
    }

    private function codeForSourceName(string $sourceName, array $catalog): ?string
    {
        foreach ($catalog as $code => $currency) {
            foreach ($currency['source_names'] as $candidate) {
                if (strcasecmp($sourceName, $candidate) === 0) {
                    return $code;
                }
            }
        }

        return null;
    }

    private function unitMultiplier(string $label, int $fallback): int
    {
        if (preg_match('/\b(10|100|1000)\b/', $label, $match)) {
            return (int) $match[1];
        }

        return max(1, $fallback);
    }

    private function number(string $value): ?float
    {
        if (! preg_match('/-?\d+(?:\.\d+)?/', str_replace(',', '', $value), $match)) {
            return null;
        }

        return (float) $match[0];
    }

    private function cleanCell(string $value): string
    {
        $value = html_entity_decode(strip_tags($value), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return trim((string) preg_replace('/\s+/u', ' ', $value));
    }

    private function lydRate(): array
    {
        return [
            'code' => 'LYD',
            'name_en' => 'Libyan Dinar',
            'source_name' => 'Libyan Dinar',
            'date' => $this->current()['meta']['source_date'] ?? null,
            'unit' => ['label' => 'One Libyan Dinar', 'multiplier' => 1],
            'rates' => [
                'average' => 1.0,
                'sell' => 1.0,
                'buy' => 1.0,
                'per_unit_average' => 1.0,
                'per_unit_sell' => 1.0,
                'per_unit_buy' => 1.0,
            ],
        ];
    }
}
