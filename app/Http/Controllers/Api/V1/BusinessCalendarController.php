<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\BusinessDayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class BusinessCalendarController extends Controller
{
    public function __construct(private readonly BusinessDayService $calendar) {}

    public function isBusinessDay(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date_format:Y-m-d'],
        ]);

        return response()->json($this->calendar->analyze($validated['date']));
    }

    public function nextBusinessDay(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'date' => ['required', 'date_format:Y-m-d'],
        ]);

        return response()->json($this->calendar->next($validated['date']));
    }

    public function businessDays(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'from' => ['required', 'date_format:Y-m-d'],
            'to' => ['required', 'date_format:Y-m-d'],
        ]);

        try {
            return response()->json($this->calendar->between($validated['from'], $validated['to']));
        } catch (RuntimeException $exception) {
            $status = match ($exception->getMessage()) {
                'invalid_date_range', 'date_range_too_large' => 422,
                default => 500,
            };

            return response()->json([
                'error' => $exception->getMessage(),
                'message' => $exception->getMessage() === 'date_range_too_large'
                    ? 'Date range must not exceed 366 days.'
                    : 'The date range is invalid.',
            ], $status);
        }
    }
}
