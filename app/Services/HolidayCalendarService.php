<?php

namespace App\Services;

class HolidayCalendarService
{
    public function forYear(int $year): array
    {
        $law = config('libya_holidays.law', []);
        $confirmed = config("libya_holidays.confirmed.{$year}", []);
        $holidays = [];

        foreach (config('libya_holidays.fixed', []) as $holiday) {
            $confirmation = $confirmed[$holiday['slug']] ?? null;
            $date = sprintf('%04d-%02d-%02d', $year, $holiday['month'], $holiday['day']);

            $holidays[] = $this->makeHoliday(
                $holiday,
                $date,
                $confirmation ? 'confirmed_official_decision' : 'statutory_fixed_date',
                $law,
                $confirmation,
            );
        }

        foreach (config('libya_holidays.floating', []) as $holiday) {
            $confirmation = $confirmed[$holiday['slug']] ?? null;

            $holidays[] = $this->makeHoliday(
                $holiday,
                $confirmation['date'] ?? null,
                $confirmation ? 'confirmed_official_decision' : 'requires_annual_confirmation',
                $law,
                $confirmation,
            );
        }

        usort($holidays, static function (array $a, array $b): int {
            if ($a['date'] === null && $b['date'] === null) {
                return $a['slug'] <=> $b['slug'];
            }

            if ($a['date'] === null) {
                return 1;
            }

            if ($b['date'] === null) {
                return -1;
            }

            return $a['date'] <=> $b['date'];
        });

        return [
            'data' => $holidays,
            'meta' => [
                'year' => $year,
                'timezone' => 'Africa/Tripoli',
                'complete' => ! in_array(null, array_column($holidays, 'date'), true),
                'law' => $law,
                'note' => 'Religious holiday dates require annual official confirmation. Substitute or bridge leave is never inferred unless an explicit decision is sourced.',
            ],
        ];
    }

    private function makeHoliday(array $holiday, ?string $date, string $status, array $law, ?array $confirmation): array
    {
        return [
            'slug' => $holiday['slug'],
            'name_en' => $holiday['name_en'],
            'name_ar' => $holiday['name_ar'],
            'date' => $date,
            'status' => $status,
            'legal_source' => $law['source_url'] ?? null,
            'confirmation_source' => $confirmation['source_url'] ?? null,
            'decision' => $confirmation['decision'] ?? null,
        ];
    }
}
