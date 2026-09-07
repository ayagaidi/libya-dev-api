<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\LibyaPhoneService;
use Illuminate\Http\JsonResponse;

class TelecomController extends Controller
{
    public function __construct(private readonly LibyaPhoneService $phones)
    {
    }

    public function operators(): JsonResponse
    {
        return response()->json([
            'data' => $this->phones->operators(),
            'meta' => [
                'country_code' => '+218',
                'note' => 'Verification strength is included per operator. Contributions with stronger current primary sources are welcome.',
            ],
        ]);
    }
}
