<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class GeoApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_municipality_points_can_return_only_mapped_records(): void
    {
        Http::fake([
            config('libya.locations.points_url') => Http::response($this->points(), 200),
        ]);

        $this->getJson('/api/v1/geo/municipality-points?mapped_only=1')
            ->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('meta.total_count', 3)
            ->assertJsonPath('meta.mapped_count', 2)
            ->assertJsonPath('data.0.slug', 'tripoli');
    }

    public function test_nearest_municipality_uses_haversine_distance(): void
    {
        Http::fake([
            config('libya.locations.points_url') => Http::response($this->points(), 200),
        ]);

        $this->getJson('/api/v1/geo/nearest?lat=32.8872&lng=13.1913&limit=1')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'tripoli')
            ->assertJsonPath('meta.distance_method', 'haversine');
    }

    public function test_nearby_endpoint_filters_by_radius(): void
    {
        Http::fake([
            config('libya.locations.points_url') => Http::response($this->points(), 200),
        ]);

        $this->getJson('/api/v1/geo/nearby?lat=32.8872&lng=13.1913&radius_km=30')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'tripoli');
    }

    public function test_geojson_is_proxied_from_the_pinned_release(): void
    {
        Http::fake([
            config('libya.locations.geojson_url') => Http::response([
                'type' => 'FeatureCollection',
                'features' => [],
            ], 200),
        ]);

        $this->get('/api/v1/geo/municipalities.geojson')
            ->assertOk()
            ->assertHeader('Content-Type', 'application/geo+json')
            ->assertJsonPath('type', 'FeatureCollection');
    }

    private function points(): array
    {
        return [
            [
                'id' => 1,
                'slug' => 'tripoli',
                'name_ar' => 'طرابلس',
                'name_en' => 'Tripoli',
                'latitude' => 32.8872,
                'longitude' => 13.1913,
                'coordinate_source' => 'test',
                'coordinate_source_id' => 'tripoli',
                'point_type' => 'named_place',
            ],
            [
                'id' => 2,
                'slug' => 'benghazi',
                'name_ar' => 'بنغازي',
                'name_en' => 'Benghazi',
                'latitude' => 32.1167,
                'longitude' => 20.0667,
                'coordinate_source' => 'test',
                'coordinate_source_id' => 'benghazi',
                'point_type' => 'named_place',
            ],
            [
                'id' => 3,
                'slug' => 'unmapped',
                'name_ar' => 'غير محدد',
                'name_en' => 'Unmapped',
                'latitude' => null,
                'longitude' => null,
                'coordinate_source' => null,
                'coordinate_source_id' => null,
                'point_type' => null,
            ],
        ];
    }
}
