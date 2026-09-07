<?php

namespace Tests\Feature;

use Tests\TestCase;

class HolidayApiTest extends TestCase
{
    public function test_2026_calendar_contains_confirmed_religious_dates_and_fixed_holidays(): void
    {
        $response = $this->getJson('/api/v1/holidays/2026')
            ->assertOk()
            ->assertJsonCount(14, 'data')
            ->assertJsonPath('meta.year', 2026)
            ->assertJsonPath('meta.complete', true);

        $holidays = collect($response->json('data'));

        $this->assertSame('2026-03-20', $holidays->firstWhere('slug', 'eid-al-fitr-day-1')['date']);
        $this->assertSame('2026-05-26', $holidays->firstWhere('slug', 'arafah-day')['date']);
        $this->assertSame('2026-06-16', $holidays->firstWhere('slug', 'hijri-new-year')['date']);
        $this->assertSame('2026-08-25', $holidays->firstWhere('slug', 'mawlid')['date']);
        $this->assertSame('2026-12-24', $holidays->firstWhere('slug', 'independence-day')['date']);
    }

    public function test_future_calendar_never_guesses_floating_religious_dates(): void
    {
        $response = $this->getJson('/api/v1/holidays/2027')
            ->assertOk()
            ->assertJsonCount(14, 'data')
            ->assertJsonPath('meta.complete', false);

        $mawlid = collect($response->json('data'))->firstWhere('slug', 'mawlid');

        $this->assertNull($mawlid['date']);
        $this->assertSame('requires_annual_confirmation', $mawlid['status']);
    }

    public function test_invalid_year_is_rejected(): void
    {
        $this->getJson('/api/v1/holidays/1899')
            ->assertStatus(422)
            ->assertJsonPath('error', 'invalid_year');
    }
}
