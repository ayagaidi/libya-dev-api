<?php

namespace App\Services;

use Carbon\CarbonImmutable;
use RuntimeException;

class BusinessDayService
{
    public function __construct(private readonly HolidayCalendarService $holidays) {}

    public function analyze(string $date): array
    {
        $day = CarbonImmutable::createFromFormat('Y-m-d', $date, 'Africa/Tripoli');

        if (! $day) {
            throw new RuntimeException('invalid_date');
        }

        $calendar = $this->holidays->forYear((int) $day->format('Y'));
        $holiday = collect($calendar['data'])->firstWhere('date', $day->format('Y-m-d'));
        $weekend = in_array($day->isoWeekday(), [5, 6], true);
        $isBusinessDay = ! $weekend && $holiday === null;
        $calendarComplete = (bool) ($calendar['meta']['complete'] ?? false);

        return [
            'data' => [
                'date' => $day->format('Y-m-d'),
                'weekday' => $day->format('l'),
                'is_business_day' => $isBusinessDay,
                'is_weekend' => $weekend,
                'holiday' => $holiday,
                'reason' => $weekend ? 'weekly_rest' : ($holiday ? 'official_holiday' : 'scheduled_workday'),
                'confidence' => $calendarComplete ? 'confirmed_calendar' : 'provisional_incomplete_holiday_calendar',
            ],
            'meta' => $this->meta($calendarComplete),
        ];
    }

    public function next(string $date): array
    {
        $cursor = CarbonImmutable::createFromFormat('Y-m-d', $date, 'Africa/Tripoli');

        if (! $cursor) {
            throw new RuntimeException('invalid_date');
        }

        for ($i = 0; $i < 370; $i++) {
            $cursor = $cursor->addDay();
            $analysis = $this->analyze($cursor->format('Y-m-d'));

            if ($analysis['data']['is_business_day']) {
                return [
                    'data' => [
                        'input_date' => $date,
                        'next_business_day' => $cursor->format('Y-m-d'),
                        'days_ahead' => $i + 1,
                        'confidence' => $analysis['data']['confidence'],
                    ],
                    'meta' => $analysis['meta'],
                ];
            }
        }

        throw new RuntimeException('business_day_not_found');
    }

    public function between(string $from, string $to): array
    {
        $start = CarbonImmutable::createFromFormat('Y-m-d', $from, 'Africa/Tripoli');
        $end = CarbonImmutable::createFromFormat('Y-m-d', $to, 'Africa/Tripoli');

        if (! $start || ! $end || $end->lessThan($start)) {
            throw new RuntimeException('invalid_date_range');
        }

        if ($start->diffInDays($end) > 366) {
            throw new RuntimeException('date_range_too_large');
        }

        $dates = [];
        $calendarComplete = true;

        for ($cursor = $start; $cursor->lessThanOrEqualTo($end); $cursor = $cursor->addDay()) {
            $analysis = $this->analyze($cursor->format('Y-m-d'));
            $calendarComplete = $calendarComplete && $analysis['data']['confidence'] === 'confirmed_calendar';

            if ($analysis['data']['is_business_day']) {
                $dates[] = $cursor->format('Y-m-d');
            }
        }

        return [
            'data' => [
                'from' => $from,
                'to' => $to,
                'inclusive' => true,
                'count' => count($dates),
                'business_days' => $dates,
                'confidence' => $calendarComplete ? 'confirmed_calendar' : 'provisional_incomplete_holiday_calendar',
            ],
            'meta' => $this->meta($calendarComplete),
        ];
    }

    private function meta(bool $calendarComplete): array
    {
        return [
            'timezone' => 'Africa/Tripoli',
            'scheduled_workweek' => ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday'],
            'weekly_rest' => ['Friday', 'Saturday'],
            'workweek_source' => 'https://lana.gov.ly/post.php?id=149105&lang=ar',
            'workweek_rule' => 'Council of Ministers Decision No. 356 of 2012 amending Decision No. 10 of 2012.',
            'holiday_calendar_complete' => $calendarComplete,
            'note' => 'This models the general public-sector schedule. Essential services, private employers, banking settlement systems and special decisions may follow different operating calendars.',
        ];
    }
}
