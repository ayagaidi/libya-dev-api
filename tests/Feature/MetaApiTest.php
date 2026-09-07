<?php

namespace Tests\Feature;

use Tests\TestCase;

class MetaApiTest extends TestCase
{
    public function test_meta_endpoint_describes_public_api(): void
    {
        $this->getJson('/api/v1/meta')
            ->assertOk()
            ->assertJsonPath('data.name', 'Libya Dev API')
            ->assertJsonPath('data.country.calling_code', '+218')
            ->assertJsonPath('meta.license', 'MIT');
    }

    public function test_openapi_document_is_available(): void
    {
        $this->get('/openapi.json')->assertOk();
    }
}
