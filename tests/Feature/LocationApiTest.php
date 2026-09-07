<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class LocationApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Cache::flush();
    }

    public function test_municipalities_are_loaded_from_pinned_dataset(): void
    {
        Http::fake([
            '*municipalities.json' => Http::response([
                ['id' => 94, 'slug' => 'tripoli-center', 'name_ar' => 'طرابلس المركز', 'name_en' => 'Tripoli Center', 'type' => 'municipality'],
            ]),
        ]);

        $this->getJson('/api/v1/locations/municipalities')
            ->assertOk()
            ->assertJsonPath('data.0.slug', 'tripoli-center')
            ->assertJsonPath('meta.source_version', '1.2.0');
    }

    public function test_location_search_matches_english_and_arabic_names(): void
    {
        Http::fake([
            '*municipalities.json' => Http::response([
                ['id' => 94, 'slug' => 'tripoli-center', 'name_ar' => 'طرابلس المركز', 'name_en' => 'Tripoli Center', 'type' => 'municipality'],
                ['id' => 1, 'slug' => 'benghazi', 'name_ar' => 'بنغازي', 'name_en' => 'Benghazi', 'type' => 'municipality'],
            ]),
        ]);

        $this->getJson('/api/v1/locations/municipalities?q=طرابلس')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'tripoli-center');
    }

    public function test_location_source_failure_returns_structured_503(): void
    {
        Http::fake(['*municipalities.json' => Http::response([], 500)]);

        $this->getJson('/api/v1/locations/municipalities')
            ->assertStatus(503)
            ->assertJsonPath('error.code', 'source_unavailable');
    }
}
