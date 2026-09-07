<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\GeoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class GeoController extends Controller
{
    public function __construct(private readonly GeoService $geo) {}

    public function points(Request $request): JsonResponse
    {
        try {
            $all = $this->geo->points();
            $mapped = $this->geo->points(true);
            $data = $request->boolean('mapped_only') ? $mapped : $all;

            return response()->json([
                'data' => $data,
                'meta' => [
                    'source' => 'Libya Locations',
                    'source_version' => config('libya.locations.version'),
                    'total_count' => count($all),
                    'mapped_count' => count($mapped),
                    'coverage_percent' => count($all) > 0 ? round(count($mapped) / count($all) * 100, 2) : 0,
                    'note' => 'Null coordinates are preserved by default rather than guessed. Use ?mapped_only=1 to return mapped records only.',
                ],
            ]);
        } catch (RuntimeException $e) {
            return $this->sourceUnavailable($e);
        }
    }

    public function geoJson(): JsonResponse
    {
        try {
            return response()->json($this->geo->geoJson())
                ->header('Content-Type', 'application/geo+json');
        } catch (RuntimeException $e) {
            return $this->sourceUnavailable($e);
        }
    }

    public function nearest(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'limit' => ['sometimes', 'integer', 'between:1,50'],
        ]);

        try {
            $data = $this->geo->nearest(
                (float) $validated['lat'],
                (float) $validated['lng'],
                (int) ($validated['limit'] ?? 5),
            );

            return response()->json([
                'data' => $data,
                'meta' => [
                    'query' => ['lat' => (float) $validated['lat'], 'lng' => (float) $validated['lng']],
                    'distance_method' => 'haversine',
                    'source_version' => config('libya.locations.version'),
                ],
            ]);
        } catch (RuntimeException $e) {
            return $this->sourceUnavailable($e);
        }
    }

    public function nearby(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'lat' => ['required', 'numeric', 'between:-90,90'],
            'lng' => ['required', 'numeric', 'between:-180,180'],
            'radius_km' => ['sometimes', 'numeric', 'between:0.1,1000'],
            'limit' => ['sometimes', 'integer', 'between:1,50'],
        ]);

        $radius = (float) ($validated['radius_km'] ?? 50);

        try {
            $data = $this->geo->nearby(
                (float) $validated['lat'],
                (float) $validated['lng'],
                $radius,
                (int) ($validated['limit'] ?? 20),
            );

            return response()->json([
                'data' => $data,
                'meta' => [
                    'query' => ['lat' => (float) $validated['lat'], 'lng' => (float) $validated['lng']],
                    'radius_km' => $radius,
                    'distance_method' => 'haversine',
                    'source_version' => config('libya.locations.version'),
                ],
            ]);
        } catch (RuntimeException $e) {
            return $this->sourceUnavailable($e);
        }
    }

    private function sourceUnavailable(RuntimeException $e): JsonResponse
    {
        return response()->json([
            'error' => 'location_source_unavailable',
            'message' => $e->getMessage(),
        ], 503);
    }
}
