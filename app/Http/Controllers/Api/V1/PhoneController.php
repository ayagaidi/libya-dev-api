<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\LibyaPhoneService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PhoneController extends Controller
{
    public function __construct(private readonly LibyaPhoneService $phones) {}

    public function normalize(Request $request): JsonResponse
    {
        $validated = $request->validate(['phone' => ['required', 'string', 'max:50']]);
        $analysis = $this->phones->analyze($validated['phone']);

        return response()->json([
            'data' => [
                'input' => $analysis['input'],
                'normalized' => $analysis['normalized'],
            ],
            'meta' => ['country_code' => '+218'],
        ]);
    }

    public function validateNumber(Request $request): JsonResponse
    {
        $validated = $request->validate(['phone' => ['required', 'string', 'max:50']]);

        return response()->json([
            'data' => $this->phones->analyze($validated['phone']),
        ]);
    }
}
