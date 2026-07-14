<?php

declare(strict_types=1);

use Misaf\VendraAffiliate\Database\Factories\AffiliateFactory;
use Misaf\VendraTenant\Models\Tenant;

beforeEach(function (): void {
    Tenant::factory()->enabled()->create()->makeCurrent();
});

it('serves only active affiliates and only their code and created_at', function (): void {
    $active = AffiliateFactory::new()->active()->create();
    $suspended = AffiliateFactory::new()->suspended()->create();

    $index = $this->getJson('/v1/affiliates', ['Accept' => 'application/vnd.api+json']);

    $index->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', (string) $active->id)
        ->assertJsonPath('data.0.attributes.code', $active->code);

    expect(array_keys($index->json('data.0.attributes')))->toBe(['code', 'created_at']);

    $filtered = $this->getJson(
        '/v1/affiliates?filter[code]=' . $active->code,
        ['Accept' => 'application/vnd.api+json'],
    );

    $filtered->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.attributes.code', $active->code);

    $this->getJson('/v1/affiliates/' . $suspended->id, ['Accept' => 'application/vnd.api+json'])
        ->assertNotFound();

    $this->getJson('/v1/affiliates/' . $active->id, ['Accept' => 'application/vnd.api+json'])
        ->assertOk()
        ->assertJsonPath('data.attributes.code', $active->code);
});
