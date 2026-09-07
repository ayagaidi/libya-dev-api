<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\BankDirectoryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function __construct(private readonly BankDirectoryService $banks) {}

    public function index(Request $request): JsonResponse
    {
        $data = $this->banks->search($request->query('q'), $request->query('city'));

        return response()->json([
            'data' => $data,
            'meta' => [
                'count' => count($data),
                'source' => $this->banks->source(),
            ],
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $bank = $this->banks->find($slug);

        if ($bank === null) {
            return response()->json([
                'error' => 'bank_not_found',
                'message' => 'No bank exists for the supplied stable slug.',
            ], 404);
        }

        return response()->json([
            'data' => $bank,
            'meta' => [
                'source' => $this->banks->source(),
            ],
        ]);
    }
}
