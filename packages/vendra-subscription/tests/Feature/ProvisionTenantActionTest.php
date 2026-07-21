<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Misaf\VendraSubscription\Actions\ProvisionTenantAction;
use Misaf\VendraSupport\Events\TenantProvisioned;
use Misaf\VendraTenant\Jobs\CacheTenantRoutesJob;

beforeEach(function (): void {
    Event::fake([TenantProvisioned::class]);
    Queue::fake();
});

it('hashes a provided owner password', function (): void {
    $result = app(ProvisionTenantAction::class)->execute([
        'name'     => 'Acme',
        'domain'   => 'acme.test',
        'username' => 'admin_acme',
        'email'    => 'admin@acme.test',
    ], password: 'secret-password');

    expect($result['password'])->toBe('secret-password')
        ->and(Hash::check('secret-password', $result['user']->password))->toBeTrue();
});

it('generates a random owner password when none is provided', function (): void {
    $result = app(ProvisionTenantAction::class)->execute([
        'name'     => 'Acme',
        'domain'   => 'acme.test',
        'username' => 'admin_acme',
        'email'    => 'admin@acme.test',
    ]);

    expect($result['password'])->toHaveLength(8)
        ->and(Hash::check($result['password'], $result['user']->password))->toBeTrue();
});

it('stamps the domain with the newly provisioned tenant even when another tenant is current', function (): void {
    $first = app(ProvisionTenantAction::class)->execute([
        'name'     => 'First',
        'domain'   => 'first.test',
        'username' => 'admin_first',
        'email'    => 'admin@first.test',
    ]);

    switchToTestTenant($first['tenant']);

    $second = app(ProvisionTenantAction::class)->execute([
        'name'     => 'Second',
        'domain'   => 'second.test',
        'username' => 'admin_second',
        'email'    => 'admin@second.test',
    ]);

    $domain = $second['tenant']->execute(
        fn() => $second['tenant']->tenantDomains()->first(),
    );

    expect($domain)->not->toBeNull()
        ->and($domain->name)->toBe('second.test')
        ->and($domain->tenant_id)->toBe($second['tenant']->getKey());
});

it('queues the tenant route cache after provisioning', function (): void {
    app(ProvisionTenantAction::class)->execute([
        'name'     => 'Acme',
        'domain'   => 'acme.test',
        'username' => 'admin_acme',
        'email'    => 'admin@acme.test',
    ]);

    Queue::assertPushed(CacheTenantRoutesJob::class);
});
