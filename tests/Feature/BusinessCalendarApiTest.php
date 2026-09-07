<?php

namespace Tests\Feature;

use Tests\TestCase;

class BusinessCalendarApiTest extends TestCase
{
    public function test_thursday_is_a_business_day_and_friday_is_weekly_rest(): void
    {
        $this->getJson('/api/v1/calendar/is-business-day?date=2026-09-10')
            ->assertOk()
            ->assertJsonPath('data.is_business_day', true)
            ->assertJsonPath('data.confidence', 'confirmed_calendar');

        $this->getJson('/api/v1/calendar/is-business-day?date=2026-09-11')
            ->assertOk()
            ->assertJsonPath('data.is_business_day', false)
            ->assertJsonPath('data.reason', 'weekly_rest');
    }

    public function test_official_holiday_is_not_a_business_day(): void
    {
        $this->getJson('/api/v1/calendar/is-business-day?date=2026-09-16')
            ->assertOk()
            ->assertJsonPath('data.is_business_day', false)
            ->assertJsonPath('data.reason', 'official_holiday')
            ->assertJsonPath('data.holiday.slug', 'martyrs-day');
    }

    public function test_next_business_day_skips_weekend(): void
    {
        $this->getJson('/api/v1/calendar/next-business-day?date=2026-09-10')
            ->assertOk()
            ->assertJsonPath('data.next_business_day', '2026-09-13')
            ->assertJsonPath('data.days_ahead', 3);
    }

    public function test_business_day_range_is_inclusive_and_excludes_holidays(): void
    {
        $this->getJson('/api/v1/calendar/business-days?from=2026-09-10&to=2026-09-16')
            ->assertOk()
            ->assertJsonPath('data.count', 4)
            ->assertJsonPath('data.business_days', [
                '2026-09-10',
                '2026-09-13',
                '2026-09-14',
                '2026-09-15',
            ]);
    }

    public function test_incomplete_future_religious_calendar_is_marked_provisional(): void
    {
        $this->getJson('/api/v1/calendar/is-business-day?date=2027-01-03')
            ->assertOk()
            ->assertJsonPath('data.confidence', 'provisional_incomplete_holiday_calendar')
            ->assertJsonPath('meta.holiday_calendar_complete', false);
    }
}
