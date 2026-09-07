<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class MetaController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'data' => [
                'name' => 'Libya Dev API',
                'api_version' => config('libya.api_version'),
                'country' => config('libya.country'),
                'features' => ['locations', 'phone', 'telecom'],
                'documentation' => url('/docs'),
                'openapi' => url('/openapi.json'),
            ],
            'meta' => [
                'open_source' => true,
                'license' => 'MIT',
            ],
        ]);
    }
}
