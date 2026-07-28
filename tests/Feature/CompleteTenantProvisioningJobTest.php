<?php

declare(strict_types=1);

use App\Jobs\CompleteTenantProvisioningJob;
use App\Models\Reseller;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Misaf\VendraSupport\Tenancy\Events\TenantProvisioned;
use Misaf\VendraTenant\Enums\TenantProvisioningStatus;
use Misaf\VendraTenant\Jobs\CacheTenantRoutesJob;
use Misaf\VendraTenant\Models\Tenant;

beforeEach(function (): void {
    Queue::fake([CacheTenantRoutesJob::class]);
});

it('completes provisioning checkpoints before activating the tenant', function (): void {
    Event::fake([TenantProvisioned::class]);
    $tenant = Tenant::factory()->create([
        'active'                   => false,
        'provisioning_status'      => TenantProvisioningStatus::Pending,
        'provisioning_should_seed' => true,
        'provisioned_at'           => null,
    ]);

    (new CompleteTenantProvisioningJob($tenant->id))->handle();

    $tenant->refresh();

    expect($tenant->active)->toBeTrue()
        ->and($tenant->provisioning_status)->toBe(TenantProvisioningStatus::Ready)
        ->and($tenant->provisioning_seeded_at)->not->toBeNull()
        ->and($tenant->routes_cached_at)->not->toBeNull()
        ->and($tenant->provisioned_at)->not->toBeNull()
        ->and($tenant->provisioning_failed_at)->toBeNull()
        ->and($tenant->provisioning_error)->toBeNull();

    Event::assertDispatched(
        TenantProvisioned::class,
        fn(TenantProvisioned $event): bool => $event->tenant->is($tenant) && $event->shouldSeed,
    );

    (new CompleteTenantProvisioningJob($tenant->id))->handle();

    Queue::assertPushedTimes(CacheTenantRoutesJob::class, 1);
});

it('records a failed checkpoint and safely retries unfinished work', function (): void {
    $tenant = Tenant::factory()->create([
        'active'                   => false,
        'provisioning_status'      => TenantProvisioningStatus::Pending,
        'provisioning_should_seed' => true,
        'provisioned_at'           => null,
    ]);
    Event::listen(TenantProvisioned::class, fn(): never => throw new RuntimeException('Seeder failed.'));
    $job = new CompleteTenantProvisioningJob($tenant->id);

    expect(fn() => $job->handle())->toThrow(RuntimeException::class, 'Seeder failed.');

    expect($tenant->refresh()->active)->toBeFalse()
        ->and($tenant->provisioning_status)->toBe(TenantProvisioningStatus::Failed)
        ->and($tenant->provisioning_seeded_at)->toBeNull()
        ->and($tenant->provisioning_failed_at)->not->toBeNull()
        ->and($tenant->provisioning_error)->toBe('Seeder failed.');

    Event::fake([TenantProvisioned::class]);
    $job->handle();

    expect($tenant->refresh()->active)->toBeTrue()
        ->and($tenant->provisioning_status)->toBe(TenantProvisioningStatus::Ready)
        ->and($tenant->provisioning_seeded_at)->not->toBeNull();
});

it('activates an unsubscribed reseller property under billing suspension', function (): void {
    Event::fake([TenantProvisioned::class]);
    $reseller = Reseller::factory()->create();
    $tenant = Tenant::factory()->create([
        'reseller_id'              => $reseller->id,
        'active'                   => false,
        'provisioning_status'      => TenantProvisioningStatus::Pending,
        'provisioning_should_seed' => false,
        'provisioned_at'           => null,
    ]);

    (new CompleteTenantProvisioningJob($tenant->id))->handle();

    expect($tenant->refresh()->active)->toBeTrue()
        ->and($tenant->billing_suspended_at)->not->toBeNull()
        ->and($tenant->provisioning_status)->toBe(TenantProvisioningStatus::Ready);

    Event::assertNotDispatched(TenantProvisioned::class);
});
