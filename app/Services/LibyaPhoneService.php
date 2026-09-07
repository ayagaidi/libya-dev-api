<?php

namespace App\Services;

class LibyaPhoneService
{
    public function analyze(string $input): array
    {
        $normalized = $this->normalize($input);
        $national = $normalized['national'];
        $prefix = $national ? substr($national, 0, 3) : null;
        $operator = $prefix ? $this->operatorForPrefix($prefix) : null;
        $structurallyValid = $national !== null && (bool) preg_match('/^09\d{8}$/', $national);
        $supportedRange = $operator !== null;

        return [
            'input' => $input,
            'normalized' => $normalized,
            'valid' => $structurallyValid && $supportedRange,
            'is_structurally_valid_mobile' => $structurallyValid,
            'is_supported_mobile_range' => $supportedRange,
            'type' => $structurallyValid ? 'mobile' : null,
            'prefix' => $prefix,
            'operator' => $operator,
            'disclaimer' => 'Operator matching is prefix/range metadata, not a live subscriber or portability lookup.',
        ];
    }

    public function normalize(string $input): array
    {
        $value = trim($input);
        $value = preg_replace('/[^0-9+]/u', '', $value) ?? '';
        $digits = preg_replace('/\D/', '', $value) ?? '';
        $national = null;

        if (str_starts_with($value, '+218')) {
            $rest = substr($digits, 3);
            $national = $rest !== '' ? '0'.$rest : null;
        } elseif (str_starts_with($digits, '00218')) {
            $rest = substr($digits, 5);
            $national = $rest !== '' ? '0'.$rest : null;
        } elseif (str_starts_with($digits, '218')) {
            $rest = substr($digits, 3);
            $national = $rest !== '' ? '0'.$rest : null;
        } elseif (str_starts_with($digits, '0')) {
            $national = $digits;
        } elseif (strlen($digits) === 9 && str_starts_with($digits, '9')) {
            $national = '0'.$digits;
        }

        if ($national === null || strlen($national) !== 10) {
            return [
                'national' => null,
                'e164' => null,
                'digits' => null,
            ];
        }

        $nsn = substr($national, 1);

        return [
            'national' => $national,
            'e164' => '+218'.$nsn,
            'digits' => '218'.$nsn,
        ];
    }

    public function operators(): array
    {
        return array_map(function (array $operator): array {
            $operator['checked_at'] = '2026-09-07';
            $operator['matching_note'] = 'Prefix identifies a published/known number range and is not a live subscriber lookup.';

            return $operator;
        }, config('libya.telecom_operators', []));
    }

    private function operatorForPrefix(string $prefix): ?array
    {
        foreach ($this->operators() as $operator) {
            if (in_array($prefix, $operator['prefixes'], true)) {
                return $operator;
            }
        }

        return null;
    }
}
