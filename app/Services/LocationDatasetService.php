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
        return $this->fetch('municipalities', config('libya.locations.municipalities_url'));
    }

    public function cities(): array
    {
        return $this->fetch('cities', config('libya.locations.cities_url'));
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

    private function fetch(string $dataset, string $url): array
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

            return array_values($data);
        });
    }
}
