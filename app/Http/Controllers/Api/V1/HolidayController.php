<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\HolidayCalendarService;
use Illuminate\Http\JsonResponse;

class HolidayController extends Controller
{
    public function __construct(private readonly HolidayCalendarService $holidays) {}

    public function index(): JsonResponse
    {
        return response()->json($this->holidays->forYear((int) now('Africa/Tripoli')->format('Y')));
    }

    public function year(int $year): JsonResponse
    {
        if ($year < 1900 || $year > 2100) {
            return response()->json([
                'error' => 'invalid_year',
                'message' => 'Year must be between 1900 and 2100.',
            ], 422);
        }

        return response()->json($this->holidays->forYear($year));
    }
}
