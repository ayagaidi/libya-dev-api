<?php

namespace Tests\Feature;

use Tests\TestCase;

class BankApiTest extends TestCase
{
    public function test_it_lists_current_central_bank_directory_entries(): void
    {
        $this->getJson('/api/v1/banks')
            ->assertOk()
            ->assertJsonCount(26, 'data')
            ->assertJsonPath('meta.count', 26)
            ->assertJsonPath('meta.source.url', 'https://cbl.gov.ly/banks/')
            ->assertJsonPath('meta.source.verification', 'current_primary_source');
    }

    public function test_it_searches_banks_in_arabic_or_english(): void
    {
        $this->getJson('/api/v1/banks?q=النوران')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'nuran-bank');

        $this->getJson('/api/v1/banks?q=Jumhouria')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.slug', 'jumhouria-bank');
    }

    public function test_it_can_filter_banks_by_city(): void
    {
        $response = $this->getJson('/api/v1/banks?city=Benghazi')->assertOk();

        $this->assertNotEmpty($response->json('data'));
        $this->assertTrue(collect($response->json('data'))->every(
            fn (array $bank): bool => $bank['city_en'] === 'Benghazi'
        ));
    }

    public function test_it_gets_a_bank_by_stable_slug_and_returns_404_for_unknown_slug(): void
    {
        $this->getJson('/api/v1/banks/libyan-foreign-bank')
            ->assertOk()
            ->assertJsonPath('data.name_ar', 'المصرف الليبي الخارجي');

        $this->getJson('/api/v1/banks/not-a-bank')
            ->assertNotFound()
            ->assertJsonPath('error', 'bank_not_found');
    }
}
