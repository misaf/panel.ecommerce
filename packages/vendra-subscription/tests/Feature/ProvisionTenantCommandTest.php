<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Misaf\VendraSubscription\Models\Account;
use Misaf\VendraSubscription\Models\Plan;
use Misaf\VendraSupport\Events\TenantProvisioned;
use Misaf\VendraTenant\Jobs\CacheTenantRoutesJob;
use Misaf\VendraTenant\Models\Tenant;
use Misaf\VendraTenant\Models\TenantDomain;
use Misaf\VendraUser\Models\User;

it('skips provisioning an existing tenant domain when requested', function (): void {
    $tenant = Tenant::factory()->create();
    TenantDomain::factory()->for($tenant)->create(['name' => 'existing.test']);

    $this->artisan('vendra-subscription:provision', [
        'name'         => 'Existing Tenant',
        'domain'       => 'existing.test',
        'username'     => 'admin',
        'email'        => 'admin@example.com',
        '--if-missing' => true,
    ])
        ->expectsOutput('Tenant domain [existing.test] already exists; provisioning skipped.')
        ->assertSuccessful();

    expect(Tenant::query()->count())->toBe(1)
        ->and(TenantDomain::query()->count())->toBe(1)
        ->and(User::query()->count())->toBe(0);
});

it('provisions a tenant with a provided password without printing it', function (): void {
    Event::fake([TenantProvisioned::class]);
    Queue::fake();

    $this->artisan('vendra-subscription:provision', [
        'name'       => 'Acme',
        'domain'     => 'acme.test',
        'username'   => 'admin_acme',
        'email'      => 'admin@acme.test',
        '--password' => 'secret-password',
    ])
        ->expectsConfirmation('Run default tenant seeders?', 'no')
        ->expectsOutputToContain('[provided]')
        ->doesntExpectOutputToContain('secret-password')
        ->assertSuccessful();

    Queue::assertPushed(CacheTenantRoutesJob::class);
});

it('provisions a website under a new account subscribed to the given plan', function (): void {
    Event::fake([TenantProvisioned::class]);
    Queue::fake();

    $plan = Plan::factory()->maxWebsites(3)->create();

    $this->artisan('vendra-subscription:provision', [
        'name'       => 'Acme',
        'domain'     => 'acme.test',
        'username'   => 'admin_acme',
        'email'      => 'admin@acme.test',
        '--password' => 'secret-password',
        '--plan'     => (string) $plan->getKey(),
    ])
        ->expectsConfirmation('Run default tenant seeders?', 'no')
        ->assertSuccessful();

    $account = Account::query()->first();

    expect($account)->not->toBeNull()
        ->and($account->tenants()->count())->toBe(1)
        ->and($account->activeSubscription()?->plan_id)->toBe($plan->getKey());

    Queue::assertPushed(CacheTenantRoutesJob::class);
});

it('fails when the provided plan cannot be resolved', function (): void {
    $this->artisan('vendra-subscription:provision', [
        'name'       => 'Acme',
        'domain'     => 'acme.test',
        'username'   => 'admin_acme',
        'email'      => 'admin@acme.test',
        '--password' => 'secret-password',
        '--plan'     => 'nonexistent',
    ])
        ->expectsConfirmation('Run default tenant seeders?', 'no')
        ->assertFailed();

    expect(Tenant::query()->count())->toBe(0)
        ->and(Account::query()->count())->toBe(0);
});

it('rejects a provided password shorter than eight characters', function (): void {
    $this->artisan('vendra-subscription:provision', [
        'name'       => 'Acme',
        'domain'     => 'acme.test',
        'username'   => 'admin_acme',
        'email'      => 'admin@acme.test',
        '--password' => 'short',
    ])
        ->expectsConfirmation('Run default tenant seeders?', 'no')
        ->assertFailed();

    expect(Tenant::query()->count())->toBe(0)
        ->and(User::query()->count())->toBe(0);
});

it('rejects an existing tenant domain without the option', function (): void {
    $tenant = Tenant::factory()->create();
    TenantDomain::factory()->for($tenant)->create(['name' => 'existing.test']);

    $this->artisan('vendra-subscription:provision', [
        'name'     => 'Duplicate Tenant',
        'domain'   => 'existing.test',
        'username' => 'admin',
        'email'    => 'admin@example.com',
    ])
        ->assertFailed();

    expect(Tenant::query()->count())->toBe(1)
        ->and(TenantDomain::query()->count())->toBe(1)
        ->and(User::query()->count())->toBe(0);
});
