<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\LocationDatasetService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class LocationController extends Controller
{
    public function __construct(private readonly LocationDatasetService $locations)
    {
    }

    public function municipalities(Request $request): JsonResponse
    {
        return $this->collectionResponse($request, 'municipalities', fn () => $this->locations->municipalities());
    }

    public function cities(Request $request): JsonResponse
    {
        return $this->collectionResponse($request, 'cities', fn () => $this->locations->cities());
    }

    public function municipality(string $slug): JsonResponse
    {
        try {
            $municipality = $this->locations->findMunicipality($slug);
        } catch (RuntimeException $e) {
            return $this->sourceUnavailable($e);
        }

        if (! $municipality) {
            return response()->json([
                'error' => ['code' => 'not_found', 'message' => 'Municipality not found.'],
            ], 404);
        }

        return response()->json([
            'data' => $municipality,
            'meta' => $this->sourceMeta(),
        ]);
    }

    private function collectionResponse(Request $request, string $dataset, callable $loader): JsonResponse
    {
        try {
            $items = $loader();
        } catch (RuntimeException $e) {
            return $this->sourceUnavailable($e);
        }

        $query = trim((string) $request->query('q', ''));
        if ($query !== '') {
            $needle = mb_strtolower($query);
            $items = array_values(array_filter($items, function (array $item) use ($needle): bool {
                $haystack = implode(' ', array_filter([
                    $item['slug'] ?? null,
                    $item['name_ar'] ?? null,
                    $item['name_en'] ?? null,
                ]));

                return str_contains(mb_strtolower($haystack), $needle);
            }));
        }

        return response()->json([
            'data' => $items,
            'meta' => array_merge($this->sourceMeta(), [
                'dataset' => $dataset,
                'count' => count($items),
                'query' => $query !== '' ? $query : null,
            ]),
        ]);
    }

    private function sourceMeta(): array
    {
        return [
            'source' => 'Libya Locations',
            'source_version' => config('libya.locations.version'),
            'source_repository' => config('libya.locations.repository'),
        ];
    }

    private function sourceUnavailable(RuntimeException $e): JsonResponse
    {
        report($e);

        return response()->json([
            'error' => [
                'code' => 'source_unavailable',
                'message' => 'The pinned locations dataset is temporarily unavailable.',
            ],
        ], 503);
    }
}
