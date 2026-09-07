<?php

namespace App\Services;

class BankDirectoryService
{
    public function search(?string $query = null, ?string $city = null): array
    {
        $banks = config('libya_banks.banks', []);
        $query = $this->normalize($query);
        $city = $this->normalize($city);

        return array_values(array_filter($banks, function (array $bank) use ($query, $city): bool {
            if ($query !== null) {
                $haystack = $this->normalize(implode(' ', [
                    $bank['slug'],
                    $bank['name_ar'],
                    $bank['name_en'],
                    $bank['city_ar'],
                    $bank['city_en'],
                ]));

                if ($haystack === null || ! str_contains($haystack, $query)) {
                    return false;
                }
            }

            if ($city !== null) {
                $bankCity = $this->normalize($bank['city_ar'].' '.$bank['city_en']);

                if ($bankCity === null || ! str_contains($bankCity, $city)) {
                    return false;
                }
            }

            return true;
        }));
    }

    public function find(string $slug): ?array
    {
        foreach (config('libya_banks.banks', []) as $bank) {
            if ($bank['slug'] === $slug) {
                return $bank;
            }
        }

        return null;
    }

    public function source(): array
    {
        return config('libya_banks.source', []);
    }

    private function normalize(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        return mb_strtolower(trim($value), 'UTF-8');
    }
}
