<?php

declare(strict_types=1);

use App\Actions\RequestStorefrontDeploymentAction;
use App\Enums\StorefrontDeploymentStatus;
use App\Jobs\ProvisionStorefrontJob;
use App\Models\StorefrontDeployment;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Misaf\VendraTenant\Models\Tenant;

function storefrontRequestData(string $slug = 'acme-flowers'): array
{
    return [
        'storefront_slug'               => $slug,
        'storefront_theme'              => 'default',
        'storefront_name_en'            => 'Acme Flowers',
        'storefront_name_fa'            => 'گل‌فروشی اکمی',
        'storefront_business_type'      => 'Florist',
        'storefront_price_currency'     => 'irr',
        'storefront_og_image'           => '/images/og.webp',
        'storefront_locality'           => 'Tehran',
        'storefront_country'            => 'ir',
        'storefront_mobile_phone'       => '09120000000',
        'storefront_office_phone'       => '02100000000',
        'storefront_contact_email'      => 'contact@acme.test',
        'storefront_hours_open'         => '08:00',
        'storefront_hours_close'        => '21:00',
        'storefront_map_query'          => '35.7,51.4',
        'storefront_whatsapp_phone'     => '+989120000000',
        'storefront_telegram_username'  => 'acmeflowers',
        'storefront_instagram_username' => 'acmeflowers',
    ];
}

beforeEach(function (): void {
    Queue::fake();
});

it('keeps an unconfigured deployment pending instead of pretending it succeeded', function (): void {
    Config::set('services.storefront.provisioner_url', null);
    $tenant = Tenant::factory()->create();

    $deployment = app(RequestStorefrontDeploymentAction::class)->execute(
        $tenant,
        'acme.test',
        storefrontRequestData(),
    );

    expect($deployment->status)->toBe(StorefrontDeploymentStatus::Pending)
        ->and($deployment->configuration['name']['en'])->toBe('Acme Flowers')
        ->and($deployment->configuration['priceCurrency'])->toBe('IRR')
        ->and($deployment->configuration['address']['country'])->toBe('IR');
    Queue::assertNothingPushed();
});

it('queues provisioning when the provider is configured', function (): void {
    Config::set('services.storefront.provisioner_url', 'https://deploy.vendra.test/storefronts');
    Config::set('services.storefront.provisioner_token', 'secret-token');
    $tenant = Tenant::factory()->create();

    $deployment = app(RequestStorefrontDeploymentAction::class)->execute(
        $tenant,
        'acme.test',
        storefrontRequestData(),
    );

    Queue::assertPushed(
        ProvisionStorefrontJob::class,
        fn(ProvisionStorefrontJob $job): bool => $job->deploymentId === $deployment->id,
    );
});

it('sends configuration to the provider and records a ready image', function (): void {
    Config::set('services.storefront.provisioner_url', 'https://deploy.vendra.test/storefronts');
    Config::set('services.storefront.provisioner_token', 'secret-token');
    Http::fake([
        'deploy.vendra.test/*' => Http::response([
            'status'       => 'ready',
            'reference'    => 'build-123',
            'image_digest' => 'sha256:abc123',
        ]),
    ]);
    $deployment = StorefrontDeployment::factory()->create();

    (new ProvisionStorefrontJob($deployment->id))->handle();

    $deployment->refresh();
    expect($deployment->status)->toBe(StorefrontDeploymentStatus::Ready)
        ->and($deployment->provider_reference)->toBe('build-123')
        ->and($deployment->image_digest)->toBe('sha256:abc123')
        ->and($deployment->deployed_at)->not->toBeNull();

    Http::assertSent(fn(Request $request): bool => $request->hasHeader('Authorization', 'Bearer secret-token')
        && 'vendra-storefront-florist' === $request['template']
        && $deployment->slug === $request['slug']);
});
