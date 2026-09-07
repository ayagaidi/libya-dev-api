<?php

namespace Tests\Feature;

use Tests\TestCase;

class TelecomApiTest extends TestCase
{
    public function test_operator_endpoint_exposes_provenance(): void
    {
        $this->getJson('/api/v1/telecom/operators')
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonPath('data.0.slug', 'almadar')
            ->assertJsonPath('data.1.slug', 'libyana')
            ->assertJsonStructure(['data' => [['slug', 'prefixes', 'verification', 'sources', 'checked_at']]]);
    }
}
