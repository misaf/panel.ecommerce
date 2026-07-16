<?php

declare(strict_types=1);

use Misaf\VendraAttribute\Database\Factories\AttributeValueFactory;
use Misaf\VendraProduct\Database\Factories\ProductFactory;
use Misaf\VendraTenant\Models\Tenant;

beforeEach(function (): void {
    Tenant::factory()->enabled()->create()->makeCurrent();
});

it('serves attribute values and validates query parameters', function (): void {
    $product = ProductFactory::new()->create();

    $first = AttributeValueFactory::new()->forAttributable($product)->create();
    $second = AttributeValueFactory::new()->forAttributable($product)->create();

    $index = $this->getJson('/v1/attribute-values', ['Accept' => 'application/vnd.api+json']);

    $index->assertOk()->assertJsonCount(2, 'data');

    $this->getJson(
        '/v1/attribute-values?filter[exclude][]=' . $second->id,
        ['Accept' => 'application/vnd.api+json'],
    )
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.id', (string) $first->id);

    $this->getJson('/v1/attribute-values?page[size]=0', ['Accept' => 'application/vnd.api+json'])
        ->assertStatus(400);

    $this->getJson('/v1/attribute-values/' . $first->id, ['Accept' => 'application/vnd.api+json'])
        ->assertOk()
        ->assertJsonPath('data.attributes.value', $first->value);

    $this->getJson(
        '/v1/attribute-values/' . $first->id . '?page[number]=1',
        ['Accept' => 'application/vnd.api+json'],
    )
        ->assertStatus(400);
});
