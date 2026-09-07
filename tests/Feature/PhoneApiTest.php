<?php

namespace Tests\Feature;

use Tests\TestCase;

class PhoneApiTest extends TestCase
{
    public function test_it_normalizes_local_mobile_number(): void
    {
        $this->postJson('/api/v1/phone/normalize', ['phone' => '091 234 5678'])
            ->assertOk()
            ->assertJsonPath('data.normalized.national', '0912345678')
            ->assertJsonPath('data.normalized.e164', '+218912345678');
    }

    public function test_it_normalizes_international_mobile_number(): void
    {
        $this->postJson('/api/v1/phone/normalize', ['phone' => '+218 92 345 6789'])
            ->assertOk()
            ->assertJsonPath('data.normalized.national', '0923456789')
            ->assertJsonPath('data.normalized.e164', '+218923456789');
    }

    public function test_it_identifies_current_verified_operator_prefix(): void
    {
        $this->postJson('/api/v1/phone/validate', ['phone' => '0931234567'])
            ->assertOk()
            ->assertJsonPath('data.valid', true)
            ->assertJsonPath('data.operator.slug', 'almadar')
            ->assertJsonPath('data.operator.verification', 'current_primary_source');
    }

    public function test_unknown_range_is_not_marked_valid(): void
    {
        $this->postJson('/api/v1/phone/validate', ['phone' => '0961234567'])
            ->assertOk()
            ->assertJsonPath('data.is_structurally_valid_mobile', true)
            ->assertJsonPath('data.is_supported_mobile_range', false)
            ->assertJsonPath('data.valid', false);
    }

    public function test_phone_is_required(): void
    {
        $this->postJson('/api/v1/phone/validate', [])->assertUnprocessable();
    }
}
