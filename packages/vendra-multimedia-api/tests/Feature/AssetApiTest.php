<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use Misaf\VendraMultimedia\Models\Multimedia;

beforeEach(function (): void {
    makeCurrentTestTenant();
});

it('filters multimedia without exposing storage internals', function (): void {
    $asset = Multimedia::query()->create([
        'model_type'            => 'test-model',
        'model_id'              => 1,
        'uuid'                  => (string) Str::uuid(),
        'collection_name'       => 'catalog',
        'name'                  => 'Hero',
        'file_name'             => 'hero.jpg',
        'mime_type'             => 'image/jpeg',
        'disk'                  => 'private',
        'conversions_disk'      => 'private',
        'size'                  => 2048,
        'manipulations'         => [],
        'custom_properties'     => [],
        'generated_conversions' => [],
        'responsive_images'     => [],
    ]);

    $response = $this->getJson('/api/content/multimedia?mimeType=image%2Fjpeg', ['Accept' => 'application/ld+json']);

    $response->assertOk()
        ->assertJsonPath('totalItems', 1)
        ->assertJsonPath('member.0.id', $asset->id)
        ->assertJsonMissingPath('member.0.disk')
        ->assertJsonMissingPath('member.0.fileName');
});
