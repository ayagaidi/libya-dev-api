<?php

namespace App\Services;

class GeoService
{
    public function __construct(private readonly LocationDatasetService $locations) {}

    public function points(bool $mappedOnly = false): array
    {
        $points = $this->locations->municipalityPoints();

        if (! $mappedOnly) {
            return $points;
        }

        return array_values(array_filter($points, fn (array $point): bool => $this->hasCoordinates($point)));
    }

    public function geoJson(): array
    {
        return $this->locations->municipalityGeoJson();
    }

    public function nearest(float $latitude, float $longitude, int $limit = 5): array
    {
        $points = $this->withDistances($latitude, $longitude);
        usort($points, fn (array $a, array $b): int => $a['distance_km'] <=> $b['distance_km']);

        return array_slice($points, 0, $limit);
    }

    public function nearby(float $latitude, float $longitude, float $radiusKm = 50, int $limit = 20): array
    {
        $points = array_values(array_filter(
            $this->withDistances($latitude, $longitude),
            fn (array $point): bool => $point['distance_km'] <= $radiusKm,
        ));

        usort($points, fn (array $a, array $b): int => $a['distance_km'] <=> $b['distance_km']);

        return array_slice($points, 0, $limit);
    }

    private function withDistances(float $latitude, float $longitude): array
    {
        return array_map(function (array $point) use ($latitude, $longitude): array {
            $point['distance_km'] = round($this->haversine(
                $latitude,
                $longitude,
                (float) $point['latitude'],
                (float) $point['longitude'],
            ), 3);

            return $point;
        }, $this->points(true));
    }

    private function hasCoordinates(array $point): bool
    {
        return is_numeric($point['latitude'] ?? null) && is_numeric($point['longitude'] ?? null);
    }

    private function haversine(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadiusKm = 6371.0088;
        $latDelta = deg2rad($lat2 - $lat1);
        $lonDelta = deg2rad($lon2 - $lon1);
        $a = sin($latDelta / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($lonDelta / 2) ** 2;

        return $earthRadiusKm * 2 * atan2(sqrt($a), sqrt(1 - $a));
    }
}
