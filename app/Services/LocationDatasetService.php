<?php

namespace App\Services;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class LocationDatasetService
{
    public function municipalities(): array
    {
        return $this->fetchArray('municipalities', config('libya.locations.municipalities_url'));
    }

    public function cities(): array
    {
        return $this->fetchArray('cities', config('libya.locations.cities_url'));
    }

    public function municipalityPoints(): array
    {
        return $this->fetchArray('municipality-points', config('libya.locations.points_url'));
    }

    public function municipalityGeoJson(): array
    {
        return $this->fetchObject('municipality-geojson', config('libya.locations.geojson_url'));
    }

    public function findMunicipality(string $slug): ?array
    {
        foreach ($this->municipalities() as $municipality) {
            if (($municipality['slug'] ?? null) === $slug) {
                return $municipality;
            }
        }

        return null;
    }

    private function fetchArray(string $dataset, string $url): array
    {
        $data = $this->fetchJson($dataset, $url);

        return array_values($data);
    }

    private function fetchObject(string $dataset, string $url): array
    {
        return $this->fetchJson($dataset, $url);
    }

    private function fetchJson(string $dataset, string $url): array
    {
        $version = config('libya.locations.version');
        $ttl = config('libya.locations.cache_ttl', 86400);

        return Cache::remember("libya-dev-api:{$dataset}:{$version}", $ttl, function () use ($url, $dataset): array {
            try {
                $response = Http::acceptJson()->timeout(10)->retry(2, 200)->get($url)->throw();
            } catch (RequestException $e) {
                throw new RuntimeException("Unable to load {$dataset} dataset from the pinned Libya Locations release.", previous: $e);
            }

            $data = $response->json();

            if (! is_array($data)) {
                throw new RuntimeException("The {$dataset} dataset returned an invalid JSON payload.");
            }

            return $data;
        });
    }
}
