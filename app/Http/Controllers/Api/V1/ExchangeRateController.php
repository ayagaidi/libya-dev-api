<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\ExchangeRateService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use RuntimeException;

class ExchangeRateController extends Controller
{
    public function __construct(private readonly ExchangeRateService $rates) {}

    public function index(): JsonResponse
    {
        try {
            return response()->json($this->rates->current());
        } catch (RuntimeException $exception) {
            return $this->sourceUnavailable($exception);
        }
    }

    public function show(string $currency): JsonResponse
    {
        try {
            $rate = $this->rates->find($currency);
        } catch (RuntimeException $exception) {
            return $this->sourceUnavailable($exception);
        }

        if (! $rate) {
            return response()->json([
                'error' => 'currency_not_found',
                'message' => 'Currency is not available in the current CBL source mapping.',
            ], 404);
        }

        return response()->json([
            'data' => $rate,
            'meta' => [
                'base_currency' => 'LYD',
                'source' => config('libya_exchange.source_name'),
                'source_url' => config('libya_exchange.source_url'),
            ],
        ]);
    }

    public function convert(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'min:0'],
            'from' => ['required', 'string', 'size:3'],
            'to' => ['required', 'string', 'size:3'],
            'rate_type' => ['sometimes', 'string', Rule::in(['average', 'buy', 'sell'])],
        ]);

        try {
            return response()->json($this->rates->convert(
                (float) $validated['amount'],
                $validated['from'],
                $validated['to'],
                $validated['rate_type'] ?? 'average',
            ));
        } catch (RuntimeException $exception) {
            if ($exception->getMessage() === 'unsupported_currency') {
                return response()->json([
                    'error' => 'unsupported_currency',
                    'message' => 'Both currencies must be LYD or available in the current CBL source mapping.',
                ], 422);
            }

            return $this->sourceUnavailable($exception);
        }
    }

    private function sourceUnavailable(RuntimeException $exception): JsonResponse
    {
        return response()->json([
            'error' => 'source_unavailable',
            'message' => $exception->getMessage(),
            'source_url' => config('libya_exchange.source_url'),
        ], 503);
    }
}
