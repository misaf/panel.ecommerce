<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Queue;
use Illuminate\Validation\ValidationException;
use Misaf\VendraStore\Actions\RequestStorefrontDeploymentAction;
use Misaf\VendraStore\Models\StorefrontImage;
use Misaf\VendraStore\Support\StorefrontProvisionRequest;

beforeEach(function (): void {
    Queue::fake();
    Config::set('vendra-container.endpoint', '');
});

it('stores the selected image and derives provisioning data from its catalog entry', function (): void {
    $image = StorefrontImage::factory()->create([
        'image'  => 'ghcr.io/misaf/storefront@sha256:abc123',
        'themes' => ['default', 'minimal'],
    ]);
    $store = createTestTenant();

    $deployment = app(RequestStorefrontDeploymentAction::class)->execute(
        $store,
        'acme.test',
        [
            ...storefrontRequestData(),
            'storefront_image_id' => $image->id,
            'storefront_theme'    => 'minimal',
        ],
    );
    $request = StorefrontProvisionRequest::for($deployment);

    expect($deployment->storefront_image_id)->toBe($image->id)
        ->and($request->image)->toBe($image->image)
        ->and($request->themes)->toBe(['default', 'minimal'])
        ->and($request->theme)->toBe('minimal');
});

it('rejects an inactive image for a new deployment', function (): void {
    $image = StorefrontImage::factory()->create([
        'themes' => ['default'],
        'active' => false,
    ]);
    $store = createTestTenant();

    expect(fn() => app(RequestStorefrontDeploymentAction::class)->execute(
        $store,
        'acme.test',
        [
            ...storefrontRequestData(),
            'storefront_image_id' => $image->id,
        ],
    ))->toThrow(ValidationException::class);
});

it('rejects a theme that is not built into the selected image', function (): void {
    $image = StorefrontImage::factory()->create(['themes' => ['default']]);
    $store = createTestTenant();

    expect(fn() => app(RequestStorefrontDeploymentAction::class)->execute(
        $store,
        'acme.test',
        [
            ...storefrontRequestData(),
            'storefront_image_id' => $image->id,
            'storefront_theme'    => 'unknown',
        ],
    ))->toThrow(ValidationException::class);
});
